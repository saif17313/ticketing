<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $bus->bus_number }} Details - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <a href="{{ route('passenger.dashboard') }}" class="text-2xl font-bold text-gray-800 hover:text-green-600">
                            🎫 BD Bus Tickets
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-800">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="javascript:history.back()" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                    <span class="mr-2">←</span> Back to Search
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Bus Overview -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                    {{ $bus->company->name }}
                                </h1>
                                <p class="text-lg text-gray-600">
                                    {{ $bus->bus_number }} • {{ $bus->bus_model }}
                                </p>
                            </div>
                            <span class="px-4 py-2 text-sm font-medium rounded-full 
                                {{ $bus->bus_type == 'AC' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $bus->bus_type }}
                            </span>
                        </div>

                        <!-- Route Info -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-800">{{ $bus->route->fromDistrict->name }}</p>
                                    <p class="text-sm text-gray-600">Starting Point</p>
                                </div>
                                <div class="flex-1 mx-6 border-t-2 border-dashed border-gray-300 relative">
                                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-white px-3 py-1 rounded-full border border-gray-300">
                                        <span class="text-sm text-gray-600">{{ $bus->route->distance_km }} km</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-800">{{ $bus->route->toDistrict->name }}</p>
                                    <p class="text-sm text-gray-600">Destination</p>
                                </div>
                            </div>
                            <div class="text-center mt-4 text-sm text-gray-600">
                                ⏱️ Estimated Duration: {{ floor($bus->route->estimated_duration_minutes / 60) }} hours {{ $bus->route->estimated_duration_minutes % 60 }} minutes
                            </div>
                        </div>

                        <!-- Bus Details -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Total Seats</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $bus->total_seats }}</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Seat Layout</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $bus->seat_layout }}</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Status</p>
                                <p class="text-lg font-semibold {{ $bus->is_active ? 'text-green-600' : 'text-gray-600' }}">
                                    {{ $bus->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                        </div>

                        <!-- Amenities -->
                        @if($bus->amenities)
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-3">✨ Amenities</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $bus->amenities) as $amenity)
                                        <span class="px-4 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-medium">
                                            {{ trim($amenity) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Company Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">🏢 Company Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="text-gray-600 w-32">Email:</span>
                                <span class="text-gray-800 font-medium">{{ $bus->company->email }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-gray-600 w-32">Phone:</span>
                                <span class="text-gray-800 font-medium">{{ $bus->company->phone }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-gray-600 w-32">Address:</span>
                                <span class="text-gray-800 font-medium">{{ $bus->company->address }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-gray-600 w-32">License:</span>
                                <span class="text-gray-800 font-medium">{{ $bus->company->license_number }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Upcoming Schedules -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">📅 Upcoming Schedules</h3>
                        
                        @if($upcomingSchedules->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingSchedules as $schedule)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-500 transition">
                                        <p class="text-sm font-semibold text-gray-800 mb-2">
                                            {{ \Carbon\Carbon::parse($schedule->journey_date)->format('D, M d, Y') }}
                                        </p>
                                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                            <span>{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span>
                                            <span>→</span>
                                            <span>{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm text-gray-600">
                                                💺 {{ $schedule->available_seats }} seats
                                            </span>
                                            <span class="text-lg font-bold text-green-600">
                                                ৳{{ number_format($schedule->base_fare, 0) }}
                                            </span>
                                        </div>
                                        <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                            Book Now
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 text-sm">No upcoming schedules available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
