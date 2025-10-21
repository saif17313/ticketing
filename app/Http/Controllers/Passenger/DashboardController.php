<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Route;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all districts for search dropdowns
        $districts = District::orderBy('name')->get();
        
        // Get popular routes (routes with most schedules)
        $popularRoutes = Route::with(['sourceDistrict', 'destinationDistrict'])
            ->withCount('buses')
            ->having('buses_count', '>', 0)
            ->orderByDesc('buses_count')
            ->limit(6)
            ->get();
        
        return view('passenger.dashboard', compact('districts', 'popularRoutes'));
    }
}
