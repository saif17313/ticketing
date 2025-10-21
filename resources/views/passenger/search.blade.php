<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Buses - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800 hover:text-green-600">
                            🎫 BD Bus Tickets
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <span class="text-sm text-gray-600">Welcome, {{ auth()->user()->name }}!</span>
                            @if(auth()->user()->role === 'owner')
                                <a href="{{ route('owner.dashboard') }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                                    🏢 Dashboard
                                </a>
                            @else
                                <a href="{{ route('passenger.dashboard') }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                                    👤 My Profile
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(isset($schedules))
                <!-- Search Summary -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                {{ $fromDistrict->name }} → {{ $toDistrict->name }}
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ \Carbon\Carbon::parse($validated['journey_date'])->format('l, F d, Y') }}
                                • {{ $schedules->count() }} bus(es) found
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('passenger.dashboard') }}" 
                                class="inline-block px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                                ← Modify Search
                            </a>
                        </div>
                    </div>
                </div>

                @if($schedules->count() > 0)
                    <!-- Filters & Sort -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                        <form method="POST" action="{{ route('search.buses') }}" class="flex flex-col md:flex-row gap-4">
                            @csrf
                            <input type="hidden" name="from_district_id" value="{{ $validated['from_district_id'] }}">
                            <input type="hidden" name="to_district_id" value="{{ $validated['to_district_id'] }}">
                            <input type="hidden" name="journey_date" value="{{ $validated['journey_date'] }}">
                            
                            <!-- Bus Type Filter -->
                            <div class="flex-1">
                                <label for="bus_type" class="block text-sm font-medium text-gray-700 mb-2">Bus Type</label>
                                <select name="bus_type" id="bus_type" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option value="">All Types</option>
                                    <option value="AC" {{ request('bus_type') == 'AC' ? 'selected' : '' }}>AC</option>
                                    <option value="Non-AC" {{ request('bus_type') == 'Non-AC' ? 'selected' : '' }}>Non-AC</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="flex-1">
                                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                                <select name="sort_by" id="sort_by" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option value="departure_time" {{ request('sort_by') == 'departure_time' ? 'selected' : '' }}>Departure Time</option>
                                    <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>

                            <!-- Apply Button -->
                            <div class="flex items-end">
                                <button type="submit" 
                                    class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                    Apply
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Search Results -->
                    <div class="space-y-4">
                        @foreach($schedules as $schedule)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                    <!-- Bus Info -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-800">
                                                    {{ $schedule->bus->company->name }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $schedule->bus->bus_number }} • {{ $schedule->bus->bus_model }}
                                                </p>
                                            </div>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                                {{ $schedule->bus->bus_type == 'AC' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $schedule->bus->bus_type }}
                                            </span>
                                        </div>

                                        <!-- Time & Duration -->
                                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                                            <div>
                                                <span class="text-gray-800 font-semibold">
                                                    {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                                                </span>
                                            </div>
                                            <div class="flex-1 border-t border-gray-300 relative">
                                                <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-white px-2 text-xs text-gray-500">
                                                    {{ floor($route->estimated_duration_minutes / 60) }}h {{ $route->estimated_duration_minutes % 60 }}m
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-800 font-semibold">
                                                    {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Amenities -->
                                        @if($schedule->bus->amenities)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach(explode(',', $schedule->bus->amenities) as $amenity)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                                        {{ trim($amenity) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Pricing & Booking -->
                                    <div class="lg:text-right">
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-600">Starting from</p>
                                            <p class="text-3xl font-bold text-green-600">
                                                ৳{{ number_format($schedule->base_fare, 0) }}
                                            </p>
                                        </div>

                                        <div class="mb-4">
                                            <p class="text-sm text-gray-600">
                                                💺 {{ $schedule->available_seats }}/{{ $schedule->bus->total_seats }} seats available
                                            </p>
                                        </div>

                                        <div class="flex flex-col gap-2">
                                            <a href="{{ route('bus.details', $schedule->bus->id) }}" 
                                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-center font-medium">
                                                View Details
                                            </a>
                                            @auth
                                                @if(Auth::user()->role === 'passenger')
                                                    <a href="{{ route('passenger.booking.seats', $schedule->id) }}" 
                                                       class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-center">
                                                        Book Now
                                                    </a>
                                                @else
                                                    <button disabled class="px-6 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium">
                                                        Passengers Only
                                                    </button>
                                                @endif
                                            @else
                                                <a href="{{ route('login') }}" 
                                                   class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-center">
                                                    Login to Book
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- No Results -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <div class="text-6xl mb-4">🚌</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">No Buses Found</h3>
                        <p class="text-gray-600 mb-6">
                            Sorry, no buses are available for this route on the selected date.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('passenger.dashboard') }}" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                Try Another Search
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <!-- Initial Search Page (before search) -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Search Buses</h1>
                    
                    <form method="POST" action="{{ route('search.buses') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- From District -->
                            <div>
                                <label for="from_district_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    From 📍
                                </label>
                                <select name="from_district_id" id="from_district_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                                    <option value="">Select City</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- To District -->
                            <div>
                                <label for="to_district_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    To 📍
                                </label>
                                <select name="to_district_id" id="to_district_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                                    <option value="">Select City</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Journey Date -->
                            <div>
                                <label for="journey_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Journey Date 📅
                                </label>
                                <input type="date" name="journey_date" id="journey_date" 
                                    value="{{ date('Y-m-d') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" 
                                class="w-full bg-green-600 text-white py-4 rounded-lg hover:bg-green-700 transition font-semibold text-lg">
                                🔍 Search Buses
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </main>
    </div>
</body>
</html>
