<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-gradient-to-r from-purple-600 to-purple-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">👨‍💼 Admin Dashboard</h1>
                        <p class="text-purple-100 text-sm mt-1">System Overview & Management</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-50 transition font-semibold">
                            🏠 Homepage
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-purple-800 text-white px-4 py-2 rounded-lg hover:bg-purple-900 transition font-semibold">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Today's Bookings -->
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">📅 Today's Bookings</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $todayBookings }}</p>
                        </div>
                        <div class="text-4xl">🎫</div>
                    </div>
                </div>

                <!-- Today's Revenue -->
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">💰 Today's Revenue</p>
                            <p class="text-3xl font-bold text-green-600">৳{{ number_format($todayRevenue, 0) }}</p>
                        </div>
                        <div class="text-4xl">💵</div>
                    </div>
                </div>

                <!-- This Week's Bookings -->
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">📊 This Week</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $weekBookings }}</p>
                            <p class="text-xs text-gray-500">৳{{ number_format($weekRevenue, 0) }}</p>
                        </div>
                        <div class="text-4xl">📈</div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">👥 Total Users</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                            <p class="text-xs text-gray-500">{{ $totalPassengers }} passengers, {{ $totalOwners }} owners</p>
                        </div>
                        <div class="text-4xl">👤</div>
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-teal-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">✅ Active Bookings</p>
                            <p class="text-3xl font-bold text-teal-600">{{ $activeBookings }}</p>
                        </div>
                        <div class="text-4xl">🎯</div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">⏳ Pending Payments</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $pendingPayments }}</p>
                        </div>
                        <div class="text-4xl">⏰</div>
                    </div>
                </div>

                <!-- Total Companies -->
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">🚌 Companies</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalCompanies }}</p>
                            <p class="text-xs text-gray-500">{{ $totalBuses }} buses</p>
                        </div>
                        <div class="text-4xl">🏢</div>
                    </div>
                </div>

                <!-- Total Routes -->
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-pink-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">📍 Total Routes</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalRoutes }}</p>
                        </div>
                        <div class="text-4xl">🗺️</div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid lg:grid-cols-2 gap-6 mb-8">
                <!-- Revenue Chart -->
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Last 7 Days Revenue</h3>
                    <canvas id="revenueChart" height="200"></canvas>
                </div>

                <!-- Booking Status Distribution -->
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">🎯 Booking Status Distribution</h3>
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">📋 Recent Bookings</h3>
                    <a href="#" class="text-purple-600 hover:text-purple-700 font-semibold text-sm">View All →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Booking Ref</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Passenger</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Company</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Route</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $booking->booking_reference }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $booking->user->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $booking->busSchedule->bus->company->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $booking->busSchedule->bus->route->districts->first()->name }} → 
                                    {{ $booking->busSchedule->bus->route->districts->last()->name }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">৳{{ number_format($booking->total_amount, 0) }}</td>
                                <td class="px-4 py-3">
                                    @if($booking->status === 'confirmed')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Confirmed</span>
                                    @elseif($booking->status === 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Cancelled</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">Expired</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json($last7Days),
                datasets: [{
                    label: 'Revenue (৳)',
                    data: @json($last7DaysRevenue),
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed', 'Pending', 'Cancelled', 'Expired'],
                datasets: [{
                    data: [
                        {{ $statusDistribution['confirmed'] }},
                        {{ $statusDistribution['pending'] }},
                        {{ $statusDistribution['cancelled'] }},
                        {{ $statusDistribution['expired'] }}
                    ],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
