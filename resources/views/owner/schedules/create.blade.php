<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Schedule - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Add New Schedule</h1>
                        <p class="text-sm text-gray-600 mt-1">Create a new trip schedule</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('owner.schedules.index') }}" class="text-gray-600 hover:text-gray-900 font-medium"> Back</a>
                        <a href="{{ route('owner.dashboard') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">Dashboard</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-200">
                <form method="POST" action="{{ route('owner.schedules.store') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="bus_id" class="block text-sm font-medium text-gray-700 mb-2">Bus <span class="text-red-600">*</span></label>
                        <select name="bus_id" id="bus_id" class="w-full px-4 py-2.5 border @error('bus_id') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            <option value="">Select Bus</option>
                            @foreach($buses as $bus)
                                <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>{{ $bus->bus_number }} ({{ $bus->route->sourceDistrict->name }}  {{ $bus->route->destinationDistrict->name }}) - {{ $bus->company->name }}</option>
                            @endforeach
                        </select>
                        @error('bus_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="journey_date" class="block text-sm font-medium text-gray-700 mb-2">Journey Date <span class="text-red-600">*</span></label>
                        <input type="date" name="journey_date" id="journey_date" value="{{ old('journey_date') }}" min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border @error('journey_date') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('journey_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500">Must be today or a future date</p>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="departure_time" class="block text-sm font-medium text-gray-700 mb-2">Departure Time <span class="text-red-600">*</span></label>
                            <input type="time" name="departure_time" id="departure_time" value="{{ old('departure_time') }}" class="w-full px-4 py-2.5 border @error('departure_time') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            @error('departure_time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="arrival_time" class="block text-sm font-medium text-gray-700 mb-2">Arrival Time <span class="text-red-600">*</span></label>
                            <input type="time" name="arrival_time" id="arrival_time" value="{{ old('arrival_time') }}" class="w-full px-4 py-2.5 border @error('arrival_time') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            @error('arrival_time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="base_fare" class="block text-sm font-medium text-gray-700 mb-2">Base Fare () <span class="text-red-600">*</span></label>
                        <input type="number" name="base_fare" id="base_fare" value="{{ old('base_fare') }}" min="100" max="10000" step="0.01" class="w-full px-4 py-2.5 border @error('base_fare') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="e.g., 800" required>
                        @error('base_fare')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500">Min: 100, Max: 10,000</p>
                    </div>
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('owner.schedules.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">Create Schedule</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>