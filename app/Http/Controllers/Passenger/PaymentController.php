<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:passenger');
    }

    // Show payment options
    public function showPaymentOptions(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Check if booking can be paid
        if ($booking->status !== 'pending') {
            return redirect()->route('passenger.bookings.show', $booking)
                ->with('error', '⚠️ This booking cannot be paid. Status: ' . $booking->status);
        }

        // Check if booking has expired
        if ($booking->isExpired()) {
            return redirect()->route('passenger.bookings.show', $booking)
                ->with('error', '⏰ This booking has expired.');
        }

        $booking->load(['busSchedule.bus.company', 'busSchedule.bus.route.districts', 'seats']);

        return view('passenger.payment.options', compact('booking'));
    }

    // Process mobile payment (bKash, Nagad, Rocket)
    public function processMobilePayment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:bkash,nagad,rocket',
            'mobile_number' => 'required|string|regex:/^01[0-9]{9}$/',
            'pin' => 'required|string|min:4|max:6',
        ]);

        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Simulate payment processing (90% success rate)
        $success = rand(1, 10) <= 9; // 90% success

        if (!$success) {
            return back()->with('error', '❌ Payment failed. Please try again.');
        }

        DB::beginTransaction();
        try {
            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'paid_at' => now(),
                'payment_details' => [
                    'mobile_number' => $request->mobile_number,
                    'method_name' => ucfirst($request->payment_method),
                ],
            ]);

            // Update booking status
            $booking->update([
                'status' => 'confirmed',
                'expires_at' => null,
            ]);

            DB::commit();

            return redirect()->route('passenger.payment.success', $booking)
                ->with('success', '🎉 Payment successful! Your booking is confirmed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '⚠️ Payment processing failed. Please try again.');
        }
    }

    // Process card payment
    public function processCardPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'card_number' => 'required|string|min:13|max:19',
            'card_name' => 'required|string|max:100',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|size:4',
            'cvv' => 'required|string|size:3',
        ]);

        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Simulate card payment (always successful in simulation)
        DB::beginTransaction();
        try {
            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'payment_method' => 'card',
                'status' => 'completed',
                'paid_at' => now(),
                'payment_details' => [
                    'card_last4' => substr($request->card_number, -4),
                    'card_name' => $request->card_name,
                    'card_type' => $this->detectCardType($request->card_number),
                ],
            ]);

            // Update booking status
            $booking->update([
                'status' => 'confirmed',
                'expires_at' => null,
            ]);

            DB::commit();

            return redirect()->route('passenger.payment.success', $booking)
                ->with('success', '🎉 Card payment successful! Your booking is confirmed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '⚠️ Payment processing failed. Please try again.');
        }
    }

    // Payment success page
    public function showSuccess(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['busSchedule.bus.company', 'busSchedule.bus.route.districts', 'seats', 'payment']);

        return view('passenger.payment.success', compact('booking'));
    }

    // Download invoice
    public function downloadInvoice(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking is confirmed
        if ($booking->status !== 'confirmed') {
            return back()->with('error', '⚠️ Invoice available only for confirmed bookings.');
        }

        $booking->load(['busSchedule.bus.company', 'busSchedule.bus.route.districts', 'seats', 'payment']);

        // For now, return HTML view (future: convert to PDF)
        return view('passenger.payment.invoice', compact('booking'));
    }

    // Helper: Detect card type from card number
    private function detectCardType($number)
    {
        $number = preg_replace('/\s+/', '', $number);
        
        if (preg_match('/^4/', $number)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $number)) {
            return 'Mastercard';
        } elseif (preg_match('/^3[47]/', $number)) {
            return 'American Express';
        }
        
        return 'Unknown';
    }
}
