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
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border border-gray-200">
                    <div class="text-center">
                        <div class="bg-gray-100 rounded-full p-3 w-14 h-14 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-2xl">🏢</span>
                        </div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase">Companies</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalCompanies }}</p>
                    </div>
                </div>

                <!-- Total Buses -->
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border border-gray-200">
                    <div class="text-center">
                        <div class="bg-gray-100 rounded-full p-3 w-14 h-14 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-2xl">🚌</span>
                        </div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase">Buses</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalBuses }}</p>
                    </div>
                </div>

                <!-- Active Schedules -->
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border border-gray-200">
                    <div class="text-center">
                        <div class="bg-gray-100 rounded-full p-3 w-14 h-14 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-2xl">📅</span>
                        </div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase">Schedules</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalSchedules }}</p>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border border-gray-200">
                    <div class="text-center">
                        <div class="bg-gray-100 rounded-full p-3 w-14 h-14 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-2xl">🎫</span>
                        </div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase">Bookings</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalBookings }}</p>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border border-gray-200">
                    <div class="text-center">
                        <div class="bg-gray-100 rounded-full p-3 w-14 h-14 mx-auto mb-3 flex items-center justify-center">
                            <span class="text-2xl">💰</span>
                        </div>
                        <p class="text-gray-600 text-xs font-medium mb-1 uppercase">Revenue</p>
                        <p class="text-2xl font-bold text-gray-800">৳{{ number_format($totalRevenue, 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Today's Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm mb-1 font-medium">📅 Today's Bookings</p>
                            <p class="text-4xl font-bold">{{ $todayBookings }}</p>
                        </div>
                        <div class="text-6xl opacity-10">🎫</div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm mb-1 font-medium">💵 Today's Revenue</p>
                            <p class="text-4xl font-bold">৳{{ number_format($todayRevenue, 0) }}</p>
                        </div>
                        <div class="text-6xl opacity-10">💰</div>
                    </div>
                </div>
            </div>

            <!-- Sales by Route -->
            @if($salesByRoute->count() > 0)
            <div class="bg-white rounded-xl shadow p-6 mb-8 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    📊 Top Routes by Revenue
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
            <div class="bg-white rounded-xl shadow p-6 mb-8 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    🎫 Recent Bookings
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
            <div class="bg-white rounded-xl shadow p-6 mb-8 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('owner.companies.create') }}" class="group bg-white border-2 border-gray-300 hover:border-green-500 text-center py-4 px-6 rounded-lg hover:bg-gray-50 transition">
                        <div class="text-3xl mb-2">🏢</div>
                        <div class="font-semibold text-gray-700 group-hover:text-green-600">Add Company</div>
                    </a>
                    <a href="{{ route('owner.buses.create') }}" class="group bg-white border-2 border-gray-300 hover:border-green-500 text-center py-4 px-6 rounded-lg hover:bg-gray-50 transition">
                        <div class="text-3xl mb-2">🚌</div>
                        <div class="font-semibold text-gray-700 group-hover:text-green-600">Add Bus</div>
                    </a>
                    <a href="{{ route('owner.schedules.create') }}" class="group bg-white border-2 border-gray-300 hover:border-green-500 text-center py-4 px-6 rounded-lg hover:bg-gray-50 transition">
                        <div class="text-3xl mb-2">📅</div>
                        <div class="font-semibold text-gray-700 group-hover:text-green-600">Add Schedule</div>
                    </a>
                    <a href="{{ route('owner.companies.index') }}" class="group bg-white border-2 border-gray-300 hover:border-green-500 text-center py-4 px-6 rounded-lg hover:bg-gray-50 transition">
                        <div class="text-3xl mb-2">📊</div>
                        <div class="font-semibold text-gray-700 group-hover:text-green-600">View All</div>
                    </a>
                </div>
            </div>

            <!-- My Buses -->
            <div class="bg-white rounded-xl shadow p-6 mb-8 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">
                        🚌 My Buses
                    </h3>
                    <div class="flex gap-2">
                        <a href="{{ route('owner.buses.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold text-sm">+ Add Bus</a>
                        <a href="{{ route('owner.buses.index') }}" class="text-green-600 hover:text-green-700 font-semibold text-sm">View All →</a>
                    </div>
                </div>
                @if($myBuses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($myBuses as $bus)
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $bus->bus_number }}</h4>
                                    <p class="text-sm text-gray-500">{{ $bus->company->name }}</p>
                                </div>
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                    {{ $bus->bus_type }}
                                </span>
                            </div>
                            <div class="space-y-2 mb-3">
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="mr-2">🗺️</span>
                                    <span>{{ $bus->route->districts->first()->name }} → {{ $bus->route->districts->last()->name }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="mr-2">💺</span>
                                    <span>{{ $bus->total_seats }} seats</span>
                                </div>
                                <div class="flex items-center text-sm font-semibold text-green-600">
                                    <span class="mr-2">💰</span>
                                    <span>৳{{ number_format($bus->fare_per_seat, 0) }}/seat</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('owner.buses.edit', $bus) }}" class="flex-1 text-center bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-semibold">
                                    ✏️ Edit
                                </a>
                                <a href="{{ route('owner.schedules.create') }}?bus_id={{ $bus->id }}" class="flex-1 text-center bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition text-sm font-semibold">
                                    📅 Schedule
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🚌</div>
                        <p class="text-gray-500 text-lg mb-4">No buses added yet</p>
                        <a href="{{ route('owner.buses.create') }}" class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                            + Add Your First Bus
                        </a>
                    </div>
                @endif
            </div>

            <!-- Upcoming Schedules -->
            <div class="bg-white rounded-xl shadow p-6 mb-8 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">
                        📅 Upcoming Schedules
                    </h3>
                    <div class="flex gap-2">
                        <a href="{{ route('owner.schedules.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold text-sm">+ Add Schedule</a>
                        <a href="{{ route('owner.schedules.index') }}" class="text-green-600 hover:text-green-700 font-semibold text-sm">View All →</a>
                    </div>
                </div>
                @if($upcomingSchedules->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date & Time</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Bus & Route</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Seats</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Fare</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($upcomingSchedules as $schedule)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($schedule->journey_date)->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-semibold text-gray-800">{{ $schedule->bus->bus_number }}</div>
                                        <div class="text-xs text-gray-500">{{ $schedule->bus->route->districts->first()->name }} → {{ $schedule->bus->route->districts->last()->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-sm">
                                            <span class="font-semibold text-gray-800">{{ $schedule->available_seats }}</span>
                                            <span class="text-gray-400">/</span>
                                            <span class="text-gray-600">{{ $schedule->bus->total_seats }}</span>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-sm font-semibold text-gray-800">৳{{ number_format($schedule->bus->fare_per_seat, 0) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('owner.schedules.edit', $schedule) }}" class="inline-block bg-gray-100 text-gray-700 px-4 py-1 rounded hover:bg-gray-200 font-semibold text-sm">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📅</div>
                        <p class="text-gray-500 text-lg mb-4">No upcoming schedules</p>
                        <a href="{{ route('owner.schedules.create') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition font-semibold">
                            + Create Schedule
                        </a>
                    </div>
                @endif
            </div>

            <!-- Recent Companies -->
            <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">
                        🏢 My Companies
                    </h3>
                    <div class="flex gap-2">
                        <a href="{{ route('owner.companies.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold text-sm">+ Add Company</a>
                        <a href="{{ route('owner.companies.index') }}" class="text-green-600 hover:text-green-700 font-semibold text-sm">View All →</a>
                    </div>
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
