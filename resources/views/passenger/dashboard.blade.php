<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Dashboard - BD Bus Tickets</title>
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
                                <a href="{{ route('passenger.bookings.index') }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                                    📋 My Bookings
                                </a>
                                <a href="{{ route('home') }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                                    🏠 Home
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

        <!-- Hero Section with Search -->
        <section class="bg-gradient-to-br from-green-50 to-blue-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold text-gray-800 mb-2">Find Your Perfect Bus</h2>
                    <p class="text-lg text-gray-600">Book comfortable bus tickets across Bangladesh</p>
                </div>

                <!-- Search Widget -->
                <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-8">
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
                                        <option value="{{ $district->id }}" {{ old('from_district_id') == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('from_district_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                        <option value="{{ $district->id }}" {{ old('to_district_id') == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('to_district_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Journey Date -->
                            <div>
                                <label for="journey_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Journey Date 📅
                                </label>
                                <input type="date" name="journey_date" id="journey_date" 
                                    value="{{ old('journey_date', date('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                                @error('journey_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="mt-6">
                            <button type="submit" 
                                class="w-full bg-green-600 text-white py-4 rounded-lg hover:bg-green-700 transition font-semibold text-lg">
                                🔍 Search Buses
                            </button>
                        </div>
                    </form>

                    @if(session('error'))
                        <div class="mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <p class="text-red-800">{{ session('error') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Popular Routes -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Popular Routes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($popularRoutes as $route)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-bold text-gray-800">
                                    {{ $route->sourceDistrict->name }}
                                </h4>
                                <span class="text-gray-400">→</span>
                                <h4 class="text-lg font-bold text-gray-800">
                                    {{ $route->destinationDistrict->name }}
                                </h4>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>📏 {{ $route->distance_km }} km</span>
                                <span>⏱️ {{ floor($route->estimated_duration_minutes / 60) }}h {{ $route->estimated_duration_minutes % 60 }}m</span>
                            </div>
                            <form method="POST" action="{{ route('search.buses') }}" class="mt-4">
                                @csrf
                                <input type="hidden" name="from_district_id" value="{{ $route->source_district_id }}">
                                <input type="hidden" name="to_district_id" value="{{ $route->destination_district_id }}">
                                <input type="hidden" name="journey_date" value="{{ date('Y-m-d') }}">
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                    Search Buses
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8">
                            <p class="text-gray-500">No popular routes available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-600 text-sm">
                    © 2025 BD Bus Tickets. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
