<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BusController extends Controller
{
    public function show(Bus $bus)
    {
        // Load all relationships
        $bus->load([
            'company',
            'route.fromDistrict',
            'route.toDistrict'
        ]);
        
        // Get upcoming schedules for this bus (next 7 days)
        $upcomingSchedules = BusSchedule::where('bus_id', $bus->id)
            ->where('journey_date', '>=', Carbon::today())
            ->where('journey_date', '<=', Carbon::today()->addDays(7))
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->orderBy('journey_date')
            ->orderBy('departure_time')
            ->get();
        
        return view('passenger.bus-details', compact('bus', 'upcomingSchedules'));
    }
}
