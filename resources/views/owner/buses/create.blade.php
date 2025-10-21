<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Bus - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">🚌 Add New Bus</h1>
                        <p class="text-green-100 text-sm mt-1">Register a new bus to your fleet</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('owner.buses.index') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition font-semibold">
                            ← Back
                        </a>
                        <a href="{{ route('owner.dashboard') }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition font-semibold">Dashboard</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Bus Information</h2>
                    <p class="text-gray-600">Fill in the details below to add a new bus</p>
                </div>
                <form method="POST" action="{{ route('owner.buses.store') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="company_id" class="block text-sm font-semibold text-gray-700 mb-2">🏢 Company <span class="text-red-500">*</span></label>
                        <select name="company_id" id="company_id" class="w-full px-4 py-3 border @error('company_id') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1">⚠️</span> {{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="route_id" class="block text-sm font-semibold text-gray-700 mb-2">🗺️ Route <span class="text-red-500">*</span></label>
                        <select name="route_id" id="route_id" class="w-full px-4 py-3 border @error('route_id') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" required>
                            <option value="">Select Route</option>
                            @foreach($routes as $route)
                                <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>{{ $route->sourceDistrict->name }}  {{ $route->destinationDistrict->name }}</option>
                            @endforeach
                        </select>
                        @error('route_id')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1">⚠️</span> {{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="bus_number" class="block text-sm font-semibold text-gray-700 mb-2">🔢 Bus Number <span class="text-red-500">*</span></label>
                        <input type="text" name="bus_number" id="bus_number" value="{{ old('bus_number') }}" class="w-full px-4 py-3 border @error('bus_number') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., DHAKA-001" required>
                        @error('bus_number')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1">⚠️</span> {{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">Must be unique, max 50 characters</p>
                    </div>
                    <div class="mb-6">
                        <label for="bus_model" class="block text-sm font-semibold text-gray-700 mb-2">🚌 Bus Model <span class="text-red-500">*</span></label>
                        <input type="text" name="bus_model" id="bus_model" value="{{ old('bus_model') }}" class="w-full px-4 py-3 border @error('bus_model') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., Volvo B11R" required>
                        @error('bus_model')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1">⚠️</span> {{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="bus_type" class="block text-sm font-semibold text-gray-700 mb-2">❄️ Bus Type <span class="text-red-500">*</span></label>
                        <select name="bus_type" id="bus_type" class="w-full px-4 py-3 border @error('bus_type') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" required>
                            <option value="">Select Type</option>
                            <option value="AC" {{ old('bus_type') == 'AC' ? 'selected' : '' }}>AC</option>
                            <option value="Non-AC" {{ old('bus_type') == 'Non-AC' ? 'selected' : '' }}>Non-AC</option>
                        </select>
                        @error('bus_type')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1">⚠️</span> {{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="total_seats" class="block text-sm font-semibold text-gray-700 mb-2">💺 Total Seats <span class="text-red-500">*</span></label>
                        <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats') }}" min="10" max="60" class="w-full px-4 py-3 border @error('total_seats') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., 40" required>
                        @error('total_seats')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1">⚠️</span> {{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">Min: 10, Max: 60</p>
                    </div>
                    <div class="mb-6">
                        <label for="seat_layout" class="block text-sm font-semibold text-gray-700 mb-2">🪑 Seat Layout <span class="text-red-500">*</span></label>
                        <select name="seat_layout" id="seat_layout" class="w-full px-4 py-3 border @error('seat_layout') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" required>
                            <option value="">Select Layout</option>
                            <option value="2x2" {{ old('seat_layout') == '2x2' ? 'selected' : '' }}>2x2 (4 seats per row)</option>
                            <option value="2x3" {{ old('seat_layout') == '2x3' ? 'selected' : '' }}>2x3 (5 seats per row)</option>
                            <option value="2x1" {{ old('seat_layout') == '2x1' ? 'selected' : '' }}>2x1 (3 seats per row)</option>
                        </select>
                        @error('seat_layout')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1">⚠️</span> {{ $message }}</p>@enderror
                    </div>
                    <div class="mb-8">
                        <label for="amenities" class="block text-sm font-semibold text-gray-700 mb-2">✨ Amenities</label>
                        <textarea name="amenities" id="amenities" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., WiFi, TV, Charging ports, Reading lights">{{ old('amenities') }}</textarea>
                        @error('amenities')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1">⚠️</span> {{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">Optional, max 500 characters</p>
                    </div>
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('owner.buses.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition font-semibold">Cancel</a>
                        <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold shadow-md hover:shadow-lg">
                            🚌 Create Bus
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>