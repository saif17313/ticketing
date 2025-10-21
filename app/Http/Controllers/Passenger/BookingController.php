<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusSchedule;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Step 1: Show seat selection
    public function showSeatSelection(BusSchedule $schedule)
    {
        // Check if user has booked 4 or more tickets in the last 24 hours
        $recentBookingsCount = Booking::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subHours(24))
            ->sum('total_seats'); // Count total seats booked, not just bookings
        
        if ($recentBookingsCount >= 4) {
            return redirect()->route('passenger.dashboard')
                ->with('error', '⚠️ You have already booked ' . $recentBookingsCount . ' seat(s) in the last 24 hours. Maximum 4 seats allowed per day. Please try again later.');
        }

        $schedule->load(['bus.company', 'bus.route.sourceDistrict', 'bus.route.destinationDistrict']);

        // Validate schedule has required date/time fields
        if (!$schedule->journey_date || !$schedule->departure_time) {
            return redirect()->route('search')
                ->with('error', '⚠️ This schedule has incomplete date/time information. Please contact support or try another bus.');
        }

        // Check if schedule has already departed - First check date, then time
        $now = \Carbon\Carbon::now();
        $journeyDate = \Carbon\Carbon::parse($schedule->journey_date);
        
        // If journey date is in the past, it's definitely departed
        if ($journeyDate->isBefore($now->startOfDay())) {
            return redirect()->back()
                ->with('error', '⚠️ This schedule has already departed. Please select another schedule.');
        }
        
        // If journey date is today, check the departure time
        if ($journeyDate->isToday()) {
            $departureTime = \Carbon\Carbon::parse($schedule->departure_time);
            $currentTime = \Carbon\Carbon::parse($now->format('H:i:s'));
            
            if ($departureTime->lessThanOrEqualTo($currentTime)) {
                return redirect()->back()
                    ->with('error', '⚠️ This schedule has already departed. Please select another schedule.');
            }
        }

        // Generate or get seats for this schedule
        $this->generateSeatsForSchedule($schedule);

        // Get seats grouped by status
        $seats = Seat::where('bus_schedule_id', $schedule->id)
            ->orderBy('seat_number')
            ->get()
            ->groupBy(function ($seat) {
                if ($seat->isBooked()) return 'booked';
                if ($seat->isLocked()) return 'locked';
                return 'available';
            });

        $availableSeats = $seats->get('available', collect());
        $bookedSeats = $seats->get('booked', collect());
        $lockedSeats = $seats->get('locked', collect());

        return view('passenger.booking.seat-selection', compact(
            'schedule',
            'availableSeats',
            'bookedSeats',
            'lockedSeats'
        ));
    }

    // Step 2: Store seat selection and lock seats
    public function storeSeatSelection(Request $request, BusSchedule $schedule)
    {
        // Check if user has booked 4 or more tickets in the last 24 hours
        $recentBookingsCount = Booking::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subHours(24))
            ->sum('total_seats');
        
        $attemptingToBook = count($request->seats ?? []);
        $totalWouldBe = $recentBookingsCount + $attemptingToBook;
        
        if ($totalWouldBe > 4) {
            return back()->with('error', '⚠️ You have already booked ' . $recentBookingsCount . ' seat(s) in the last 24 hours. You can only book ' . (4 - $recentBookingsCount) . ' more seat(s). Maximum 4 seats allowed per day.');
        }

        $request->validate([
            'seats' => 'required|array|min:1|max:4',
            'seats.*' => 'required|exists:seats,id',
        ], [
            'seats.required' => 'Please select at least one seat.',
            'seats.max' => 'You can select maximum 4 seats at a time.',
        ]);

        DB::beginTransaction();
        try {
            // Verify all selected seats are available
            $selectedSeats = Seat::whereIn('id', $request->seats)
                ->where('bus_schedule_id', $schedule->id)
                ->lockForUpdate()
                ->get();

            foreach ($selectedSeats as $seat) {
                if (!$seat->isAvailable()) {
                    DB::rollBack();
                    return back()->with('error', '⚠️ Seat ' . $seat->seat_number . ' is no longer available. Please select different seats.');
                }
            }

            // Lock selected seats for 10 minutes
            foreach ($selectedSeats as $seat) {
                $seat->lock(10);
            }

            // Store seat IDs in session
            session([
                'booking_seats' => $request->seats,
                'booking_schedule_id' => $schedule->id,
                'seats_locked_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('passenger.booking.details', $schedule)
                ->with('success', '✅ Seats locked! Please complete booking within 10 minutes.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '⚠️ Failed to lock seats. Please try again.');
        }
    }

    // Step 3: Show passenger details form
    public function showPassengerDetails(BusSchedule $schedule)
    {
        // Verify seats are locked in session
        if (!session('booking_seats') || session('booking_schedule_id') != $schedule->id) {
            return redirect()->route('passenger.booking.seats', $schedule)
                ->with('error', '⚠️ Please select seats first.');
        }

        // Check if seats lock has expired (10 minutes)
        $lockedAt = session('seats_locked_at');
        if ($lockedAt && now()->diffInMinutes($lockedAt) > 10) {
            $this->unlockSessionSeats();
            return redirect()->route('passenger.booking.seats', $schedule)
                ->with('error', '⏱️ Your seat selection has expired. Please select seats again.');
        }

        $schedule->load(['bus.company', 'bus.route.sourceDistrict', 'bus.route.destinationDistrict']);

        // Get selected seats
        $seats = Seat::whereIn('id', session('booking_seats'))->get();
        $totalAmount = $seats->sum(function ($seat) use ($schedule) {
            return $schedule->base_fare;
        });

        return view('passenger.booking.passenger-details', compact('schedule', 'seats', 'totalAmount'));
    }

    // Step 4: Store passenger details
    public function storePassengerDetails(Request $request, BusSchedule $schedule)
    {
        $request->validate([
            'passenger_name' => 'required|string|max:100',
            'passenger_phone' => 'required|string|regex:/^01[0-9]{9}$/',
            'passenger_email' => 'nullable|email|max:100',
            'booking_type' => 'required|in:book,direct_pay',
        ]);

        // Store passenger details in session
        session([
            'passenger_details' => [
                'name' => $request->passenger_name,
                'phone' => $request->passenger_phone,
                'email' => $request->passenger_email,
            ],
            'booking_type' => $request->booking_type,
        ]);

        return redirect()->route('passenger.booking.confirm', $schedule);
    }

    // Step 5: Show confirmation page
    public function showConfirmation(BusSchedule $schedule)
    {
        // Verify all required session data exists
        if (!session('booking_seats') || !session('passenger_details')) {
            return redirect()->route('passenger.booking.seats', $schedule)
                ->with('error', '⚠️ Session expired. Please start booking again.');
        }

        $schedule->load(['bus.company', 'bus.route.sourceDistrict', 'bus.route.destinationDistrict']);

        $seats = Seat::whereIn('id', session('booking_seats'))->get();
        $totalAmount = $seats->sum(function ($seat) use ($schedule) {
            return $schedule->base_fare;
        });

        $passengerDetails = session('passenger_details');
        $bookingType = session('booking_type');

        return view('passenger.booking.confirmation', compact(
            'schedule',
            'seats',
            'totalAmount',
            'passengerDetails',
            'bookingType'
        ));
    }

    // Step 6: Confirm and create booking
    public function confirmBooking(BusSchedule $schedule)
    {
        // Verify session data
        if (!session('booking_seats') || !session('passenger_details')) {
            return redirect()->route('passenger.booking.seats', $schedule)
                ->with('error', '⚠️ Session expired. Please start booking again.');
        }

        DB::beginTransaction();
        try {
            $seatIds = session('booking_seats');
            $seats = Seat::whereIn('id', $seatIds)
                ->lockForUpdate()
                ->get();

            // Verify all seats are still locked
            foreach ($seats as $seat) {
                if (!$seat->isLocked() && !$seat->isAvailable()) {
                    DB::rollBack();
                    $this->clearBookingSession();
                    return redirect()->route('passenger.booking.seats', $schedule)
                        ->with('error', '⚠️ Some seats are no longer available. Please select again.');
                }
            }

            $totalAmount = $seats->count() * $schedule->base_fare;
            $bookingType = session('booking_type');

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'bus_schedule_id' => $schedule->id,
                'total_seats' => $seats->count(),
                'total_amount' => $totalAmount,
                'booking_type' => $bookingType,
                'status' => 'pending', // Always pending initially, payment confirms it
                'payment_deadline' => now()->addMinutes(30), // 30 minutes to pay
                'expires_at' => $bookingType === 'book' ? now()->addHours(24) : null,
                'passenger_details' => session('passenger_details'),
            ]);

            // Book all seats
            foreach ($seats as $seat) {
                $seat->book($booking->id);
            }

            // Update schedule available seats
            $schedule->decrement('available_seats', $seats->count());

            DB::commit();

            // Clear session
            $this->clearBookingSession();

            // Redirect based on booking type
            if ($bookingType === 'direct_pay') {
                return redirect()->route('passenger.booking.payment', $booking)
                    ->with('success', '✅ Booking created! Please complete payment within 30 minutes.');
            }

            return redirect()->route('passenger.bookings.show', $booking)
                ->with('success', '🎉 Booking created! Please complete payment within 30 minutes. Reference: ' . $booking->booking_reference);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '⚠️ Booking failed. Please try again. Error: ' . $e->getMessage());
        }
    }

    // Show user's bookings
    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['busSchedule.bus.company', 'busSchedule.bus.route.sourceDistrict', 'busSchedule.bus.route.destinationDistrict', 'seats'])
            ->latest()
            ->paginate(10);

        return view('passenger.bookings.index', compact('bookings'));
    }

    // Show single booking
    public function showBooking(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        $booking->load(['busSchedule.bus.company', 'busSchedule.bus.route.sourceDistrict', 'busSchedule.bus.route.destinationDistrict', 'seats', 'payment']);

        return view('passenger.bookings.show', compact('booking'));
    }

    // Cancel booking
    public function cancelBooking(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        if (!$booking->canBeCancelled()) {
            return back()->with('error', '⚠️ This booking cannot be cancelled.');
        }

        DB::beginTransaction();
        try {
            // Release all seats
            $seats = $booking->seats;
            foreach ($seats as $seat) {
                $seat->release();
            }

            // Update schedule available seats
            $booking->busSchedule->increment('available_seats', $seats->count());

            // Cancel booking
            $booking->update(['status' => 'cancelled']);

            DB::commit();

            return back()->with('success', '✅ Booking cancelled successfully. Seats have been released.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '⚠️ Failed to cancel booking. Please try again.');
        }
    }

    // Helper: Generate seats for schedule if not exists
    private function generateSeatsForSchedule(BusSchedule $schedule)
    {
        $existingSeatsCount = Seat::where('bus_schedule_id', $schedule->id)->count();

        if ($existingSeatsCount > 0) {
            return; // Seats already generated
        }

        $bus = $schedule->bus;
        $totalSeats = $bus->total_seats;
        $busType = $bus->bus_type;

        // Determine layout based on bus type
        // Non-AC = 2x2 (4 seats per row: A, B, C, D)
        // AC = 2x1 (3 seats per row: A, B, C)
        $seats = [];
        
        if ($busType === 'Non-AC') {
            // 2x2 layout for Non-AC buses (A-D per row)
            $rows = ceil($totalSeats / 4);
            $seatNumber = 1;
            
            for ($row = 1; $row <= $rows; $row++) {
                foreach (['A', 'B', 'C', 'D'] as $column) {
                    if ($seatNumber > $totalSeats) break;
                    $seats[] = [
                        'bus_schedule_id' => $schedule->id,
                        'seat_number' => $column . $row,
                        'seat_type' => 'standard',
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $seatNumber++;
                }
            }
        } else {
            // 2x1 layout for AC buses (A-C per row)
            $rows = ceil($totalSeats / 3);
            $seatNumber = 1;
            
            for ($row = 1; $row <= $rows; $row++) {
                foreach (['A', 'B', 'C'] as $column) {
                    if ($seatNumber > $totalSeats) break;
                    $seats[] = [
                        'bus_schedule_id' => $schedule->id,
                        'seat_number' => $column . $row,
                        'seat_type' => 'standard',
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $seatNumber++;
                }
            }
        }

        Seat::insert($seats);
    }

    // Helper: Unlock seats in session
    private function unlockSessionSeats()
    {
        if (session('booking_seats')) {
            $seats = Seat::whereIn('id', session('booking_seats'))->get();
            foreach ($seats as $seat) {
                if ($seat->isLocked()) {
                    $seat->unlock();
                }
            }
        }
        $this->clearBookingSession();
    }

    // Helper: Clear booking session
    private function clearBookingSession()
    {
        session()->forget(['booking_seats', 'booking_schedule_id', 'seats_locked_at', 'passenger_details', 'booking_type']);
    }
}
