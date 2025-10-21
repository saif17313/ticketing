<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\BusCompany;
use App\Models\Bus;
use App\Models\Route;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's statistics
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $todayRevenue = Booking::whereDate('created_at', today())
            ->where('status', 'confirmed')
            ->sum('total_amount');

        // This week's statistics
        $weekBookings = Booking::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        
        $weekRevenue = Booking::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->where('status', 'confirmed')->sum('total_amount');

        // All time statistics
        $totalUsers = User::count();
        $totalPassengers = User::where('role', 'passenger')->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalCompanies = BusCompany::count();
        $totalBuses = Bus::count();
        $totalRoutes = Route::count();

        // Booking statistics
        $activeBookings = Booking::where('status', 'confirmed')
            ->whereHas('busSchedule', function($q) {
                $q->where('departure_date', '>=', today());
            })->count();

        $pendingPayments = Booking::where('status', 'pending')->count();
        $expiredToday = Booking::where('status', 'expired')
            ->whereDate('updated_at', today())->count();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'busSchedule.bus.company', 'busSchedule.bus.route.districts'])
            ->latest()
            ->limit(10)
            ->get();

        // Last 7 days revenue for chart
        $last7Days = [];
        $last7DaysRevenue = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('M d');
            $last7DaysRevenue[] = Booking::whereDate('created_at', $date)
                ->where('status', 'confirmed')
                ->sum('total_amount');
        }

        // Booking status distribution
        $statusDistribution = [
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'expired' => Booking::where('status', 'expired')->count(),
        ];

        // Top routes
        $topRoutes = Route::withCount(['buses' => function($q) {
                $q->whereHas('schedules.bookings', function($q2) {
                    $q2->where('status', 'confirmed');
                });
            }])
            ->having('buses_count', '>', 0)
            ->orderByDesc('buses_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayBookings',
            'todayRevenue',
            'weekBookings',
            'weekRevenue',
            'totalUsers',
            'totalPassengers',
            'totalOwners',
            'totalCompanies',
            'totalBuses',
            'totalRoutes',
            'activeBookings',
            'pendingPayments',
            'expiredToday',
            'recentBookings',
            'last7Days',
            'last7DaysRevenue',
            'statusDistribution'
        ));
    }
}
