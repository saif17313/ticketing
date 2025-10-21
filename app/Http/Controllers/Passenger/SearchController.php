<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Route;
use App\Models\BusSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index()
    {
        // Get all districts for search form
        $districts = District::orderBy('name')->get();
        
        return view('passenger.search', compact('districts'));
    }
    
    public function search(Request $request)
    {
        // Validate search inputs
        $validated = $request->validate([
            'from_district_id' => 'required|exists:districts,id',
            'to_district_id' => 'required|exists:districts,id|different:from_district_id',
            'journey_date' => 'required|date|after_or_equal:today',
        ], [
            'to_district_id.different' => 'Source and destination must be different.',
            'journey_date.after_or_equal' => 'Please select today or a future date.',
        ]);
        
        // Find the route
        $route = Route::where('source_district_id', $validated['from_district_id'])
            ->where('destination_district_id', $validated['to_district_id'])
            ->with(['sourceDistrict', 'destinationDistrict'])
            ->first();
        
        if (!$route) {
            return back()->with('error', 'No buses available for this route.');
        }
        
        // Get search parameters for filters and sorting
        $busType = $request->input('bus_type');
        $sortBy = $request->input('sort_by', 'departure_time'); // default sort
        
        // Build query for schedules
        $schedulesQuery = BusSchedule::where('journey_date', $validated['journey_date'])
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->whereHas('bus', function($query) use ($route, $busType) {
                $query->where('route_id', $route->id)
                      ->where('is_active', true);
                
                // Filter by bus type if selected
                if ($busType) {
                    $query->where('bus_type', $busType);
                }
            })
            ->with([
                'bus.company',
                'bus.route.sourceDistrict',
                'bus.route.destinationDistrict'
            ]);
        
        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $schedulesQuery->orderBy('base_fare', 'asc');
                break;
            case 'price_high':
                $schedulesQuery->orderBy('base_fare', 'desc');
                break;
            case 'departure_time':
            default:
                $schedulesQuery->orderBy('departure_time', 'asc');
                break;
        }
        
        $schedules = $schedulesQuery->get();
        
        // Get all districts for search form
        $districts = District::orderBy('name')->get();
        
        // Get selected districts for display
        $fromDistrict = District::find($validated['from_district_id']);
        $toDistrict = District::find($validated['to_district_id']);
        
        return view('passenger.search', compact(
            'schedules',
            'districts',
            'route',
            'fromDistrict',
            'toDistrict',
            'validated'
        ));
    }
}
