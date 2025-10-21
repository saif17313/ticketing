<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Modern Header with Gradient -->
        <header class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">🚌 BD Bus Tickets</h1>
                        <p class="text-green-100 text-sm mt-1">Owner Dashboard</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right mr-4">
                            <p class="text-white font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-green-100 text-sm">Owner Account</p>
                        </div>
                        <a href="{{ route('home') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition font-semibold">Home</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition font-semibold">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 mb-8 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                        <p class="text-gray-600">Manage your bus companies, routes, and schedules all in one place.</p>
                    </div>
                    <div class="hidden md:block text-6xl">🚍</div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total Companies -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="text-center">
                        <div class="bg-green-100 rounded-full p-3 w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-3xl">🏢</span>
                        </div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Companies</p>
                        <p class="text-3xl font-bold text-green-600">{{ $totalCompanies }}</p>
                    </div>
                </div>

                <!-- Total Buses -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-full p-3 w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-3xl">🚌</span>
                        </div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Buses</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalBuses }}</p>
                    </div>
                </div>

                <!-- Active Schedules -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="text-center">
                        <div class="bg-orange-100 rounded-full p-3 w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-3xl">📅</span>
                        </div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Schedules</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $totalSchedules }}</p>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="text-center">
                        <div class="bg-purple-100 rounded-full p-3 w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-3xl">🎫</span>
                        </div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Bookings</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $totalBookings }}</p>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="text-center">
                        <div class="bg-teal-100 rounded-full p-3 w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-3xl">💰</span>
                        </div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Revenue</p>
                        <p class="text-2xl font-bold text-teal-600">৳{{ number_format($totalRevenue, 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Today's Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm mb-1">📅 Today's Bookings</p>
                            <p class="text-4xl font-bold">{{ $todayBookings }}</p>
                        </div>
                        <div class="text-6xl opacity-20">🎫</div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-100 text-sm mb-1">💵 Today's Revenue</p>
                            <p class="text-4xl font-bold">৳{{ number_format($todayRevenue, 0) }}</p>
                        </div>
                        <div class="text-6xl opacity-20">💰</div>
                    </div>
                </div>
            </div>

            <!-- Sales by Route -->
            @if($salesByRoute->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-600 rounded-lg px-3 py-1 text-sm mr-3">📊 Top Routes by Revenue</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Route</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Total Bookings</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($salesByRoute as $sale)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">🗺️</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $sale->route_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                                        {{ $sale->total_bookings }} tickets
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-bold text-teal-600">৳{{ number_format($sale->total_revenue, 0) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Recent Bookings -->
            @if($recentBookings->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-600 rounded-lg px-3 py-1 text-sm mr-3">🎫 Recent Bookings</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Booking Ref</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Passenger</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Company</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Route</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $booking->booking_reference }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $booking->user->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $booking->busSchedule->bus->company->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $booking->busSchedule->bus->route->districts->first()->name }} → 
                                    {{ $booking->busSchedule->bus->route->districts->last()->name }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800 text-right">৳{{ number_format($booking->total_amount, 0) }}</td>
                                <td class="px-4 py-3 text-center">
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
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-600 rounded-lg px-3 py-1 text-sm mr-3">Quick Actions</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('owner.companies.create') }}" class="group bg-gradient-to-br from-green-500 to-green-600 text-white text-center py-4 px-6 rounded-xl hover:from-green-600 hover:to-green-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <div class="text-3xl mb-2">🏢</div>
                        <div class="font-semibold">Add Company</div>
                    </a>
                    <a href="{{ route('owner.buses.create') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white text-center py-4 px-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <div class="text-3xl mb-2">🚌</div>
                        <div class="font-semibold">Add Bus</div>
                    </a>
                    <a href="{{ route('owner.schedules.create') }}" class="group bg-gradient-to-br from-orange-500 to-orange-600 text-white text-center py-4 px-6 rounded-xl hover:from-orange-600 hover:to-orange-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <div class="text-3xl mb-2">📅</div>
                        <div class="font-semibold">Add Schedule</div>
                    </a>
                    <a href="{{ route('owner.companies.index') }}" class="group bg-gradient-to-br from-gray-500 to-gray-600 text-white text-center py-4 px-6 rounded-xl hover:from-gray-600 hover:to-gray-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <div class="text-3xl mb-2">📊</div>
                        <div class="font-semibold">View All</div>
                    </a>
                </div>
            </div>

            <!-- Recent Companies -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-green-100 text-green-600 rounded-lg px-3 py-1 text-sm mr-3">Recent Companies</span>
                    </h3>
                    <a href="{{ route('owner.companies.index') }}" class="text-green-600 hover:text-green-700 font-semibold text-sm">View All →</a>
                </div>
                @if($recentCompanies->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentCompanies as $company)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 font-bold text-lg">{{ substr($company->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $company->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $company->license_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $company->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $company->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($company->is_active)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                ✓ Active
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                ✗ Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('owner.companies.edit', $company) }}" class="text-green-600 hover:text-green-900 font-semibold">Edit →</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📋</div>
                        <p class="text-gray-500 text-lg mb-4">No companies yet</p>
                        <a href="{{ route('owner.companies.create') }}" class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition font-semibold">
                            + Create Your First Company
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
