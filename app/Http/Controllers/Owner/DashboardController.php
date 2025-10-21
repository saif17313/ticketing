<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusCompany;
use App\Models\Bus;
use App\Models\BusSchedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Get total bookings and revenue
        $totalBookings = Booking::whereHas('busSchedule.bus.company', function($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })->where('status', 'confirmed')->count();

        $totalRevenue = Booking::whereHas('busSchedule.bus.company', function($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })->where('status', 'confirmed')->sum('total_amount');

        // Today's statistics
        $todayBookings = Booking::whereHas('busSchedule.bus.company', function($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })->whereDate('created_at', today())->where('status', 'confirmed')->count();

        $todayRevenue = Booking::whereHas('busSchedule.bus.company', function($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })->whereDate('created_at', today())->where('status', 'confirmed')->sum('total_amount');

        // Sales by route (Top 5 routes by revenue)
        $salesByRoute = DB::table('bookings')
            ->join('bus_schedules', 'bookings.bus_schedule_id', '=', 'bus_schedules.id')
            ->join('buses', 'bus_schedules.bus_id', '=', 'buses.id')
            ->join('bus_companies', 'buses.company_id', '=', 'bus_companies.id')
            ->join('routes', 'buses.route_id', '=', 'routes.id')
            ->where('bus_companies.owner_id', $owner->id)
            ->where('bookings.status', 'confirmed')
            ->select(
                'routes.id as route_id',
                DB::raw('COUNT(bookings.id) as total_bookings'),
                DB::raw('SUM(bookings.total_amount) as total_revenue')
            )
            ->groupBy('routes.id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Get route details
        foreach ($salesByRoute as $sale) {
            $route = DB::table('routes')
                ->join('route_district', 'routes.id', '=', 'route_district.route_id')
                ->join('districts', 'route_district.district_id', '=', 'districts.id')
                ->where('routes.id', $sale->route_id)
                ->orderBy('route_district.order')
                ->select('districts.name')
                ->get();
            
            $sale->route_name = $route->first()->name . ' → ' . $route->last()->name;
        }

        // Recent bookings
        $recentBookings = Booking::with(['user', 'busSchedule.bus.company', 'busSchedule.bus.route.districts'])
            ->whereHas('busSchedule.bus.company', function($query) use ($owner) {
                $query->where('owner_id', $owner->id);
            })
            ->latest()
            ->take(10)
            ->get();

        // Get recent companies
        $recentCompanies = BusCompany::where('owner_id', $owner->id)
            ->latest()
            ->take(5)
            ->get();

        // My Buses - Latest 5 buses
        $myBuses = Bus::whereHas('company', function($q) use ($owner) {
            $q->where('owner_id', $owner->id);
        })->with(['company', 'route.districts'])->latest()->take(5)->get();

        // Upcoming Schedules - Next 6 schedules
        $upcomingSchedules = BusSchedule::whereHas('bus.company', function($q) use ($owner) {
            $q->where('owner_id', $owner->id);
        })
        ->with(['bus.company', 'bus.route.districts'])
        ->where('journey_date', '>=', now()->toDateString())
        ->orderBy('journey_date')
        ->orderBy('departure_time')
        ->take(6)
        ->get();

        return view('owner.dashboard', compact(
            'totalCompanies',
            'totalBuses',
            'totalSchedules',
            'totalBookings',
            'totalRevenue',
            'todayBookings',
            'todayRevenue',
            'salesByRoute',
            'recentBookings',
            'recentCompanies',
            'myBuses',
            'upcomingSchedules'
        ));
    }
}
