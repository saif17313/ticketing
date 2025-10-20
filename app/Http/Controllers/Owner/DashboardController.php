<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusCompany;
use App\Models\Bus;
use App\Models\BusSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $owner = Auth::user();
        
        // Get statistics for the dashboard
        $totalCompanies = BusCompany::where('owner_id', $owner->id)->count();
        $totalBuses = Bus::whereHas('company', function($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })->count();
        
        $totalSchedules = BusSchedule::whereHas('bus.company', function($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })->where('journey_date', '>=', now()->toDateString())->count();

        // Get recent companies
        $recentCompanies = BusCompany::where('owner_id', $owner->id)
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalCompanies',
            'totalBuses',
            'totalSchedules',
            'recentCompanies'
        ));
    }
}
