<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusSchedule;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    // Show all schedules for the logged-in owner
    public function index()
    {
        $schedules = BusSchedule::whereHas('bus.company', function($query) {
            $query->where('owner_id', Auth::id());
        })
        ->with(['bus.company', 'bus.route.sourceDistrict', 'bus.route.destinationDistrict'])
        ->orderBy('journey_date', 'desc')
        ->orderBy('departure_time', 'desc')
        ->paginate(15);
        
        return view('owner.schedules.index', compact('schedules'));
    }

    // Show form to create a new schedule
    public function create()
    {
        // Get only this owner's buses
        $buses = Bus::whereHas('company', function($query) {
            $query->where('owner_id', Auth::id());
        })
        ->where('is_active', true)
        ->with(['company', 'route.sourceDistrict', 'route.destinationDistrict'])
        ->get();

        return view('owner.schedules.create', compact('buses'));
    }

    // Store a new schedule
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => ['required', 'exists:buses,id'],
            'journey_date' => ['required', 'date', 'after_or_equal:today'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_time' => ['required', 'date_format:H:i', 'after:departure_time'],
            'base_fare' => ['required', 'numeric', 'min:100', 'max:10000'],
        ], [
            'journey_date.after_or_equal' => 'Journey date cannot be in the past.',
            'arrival_time.after' => 'Arrival time must be after departure time.',
            'base_fare.min' => 'Minimum fare is ৳100.',
            'base_fare.max' => 'Maximum fare is ৳10,000.',
        ]);

        // Verify the bus belongs to this owner
        $bus = Bus::findOrFail($request->bus_id);
        if ($bus->company->owner_id !== Auth::id()) {
            abort(403, 'You can only create schedules for your own buses.');
        }

        // Check for duplicate schedule
        $exists = BusSchedule::where('bus_id', $request->bus_id)
            ->where('journey_date', $request->journey_date)
            ->where('departure_time', $request->departure_time)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'departure_time' => 'A schedule already exists for this bus on this date and time.'
            ])->withInput();
        }

        BusSchedule::create([
            'bus_id' => $request->bus_id,
            'journey_date' => $request->journey_date,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'base_fare' => $request->base_fare,
            'available_seats' => $bus->total_seats, // Initially all seats are available
            'status' => 'scheduled',
        ]);

        return redirect()->route('owner.schedules.index')
            ->with('success', 'Schedule created successfully!');
    }

    // Show form to edit a schedule
    public function edit(BusSchedule $schedule)
    {
        // Make sure the owner can only edit their own schedules
        if ($schedule->bus->company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $buses = Bus::whereHas('company', function($query) {
            $query->where('owner_id', Auth::id());
        })
        ->where('is_active', true)
        ->with(['company', 'route.sourceDistrict', 'route.destinationDistrict'])
        ->get();

        return view('owner.schedules.edit', compact('schedule', 'buses'));
    }

    // Update a schedule
    public function update(Request $request, BusSchedule $schedule)
    {
        // Make sure the owner can only update their own schedules
        if ($schedule->bus->company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'bus_id' => ['required', 'exists:buses,id'],
            'journey_date' => ['required', 'date'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_time' => ['required', 'date_format:H:i', 'after:departure_time'],
            'base_fare' => ['required', 'numeric', 'min:100', 'max:10000'],
            'status' => ['required', 'in:scheduled,departed,arrived,cancelled'],
        ]);

        // Verify the bus belongs to this owner
        $bus = Bus::findOrFail($request->bus_id);
        if ($bus->company->owner_id !== Auth::id()) {
            abort(403, 'You can only assign schedules to your own buses.');
        }

        $schedule->update([
            'bus_id' => $request->bus_id,
            'journey_date' => $request->journey_date,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'base_fare' => $request->base_fare,
            'status' => $request->status,
        ]);

        return redirect()->route('owner.schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }

    // Delete a schedule
    public function destroy(BusSchedule $schedule)
    {
        // Make sure the owner can only delete their own schedules
        if ($schedule->bus->company->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $schedule->delete();

        return redirect()->route('owner.schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }
}
