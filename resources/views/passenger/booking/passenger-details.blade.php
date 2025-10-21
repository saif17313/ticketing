<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Details - Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">👤 Passenger Details</h1>
                    <a href="{{ route('passenger.booking.seats', $schedule) }}" class="text-green-600 hover:text-green-700 font-semibold">← Change Seats</a>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Timer Warning -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">⏱️</span>
                    <div>
                        <p class="text-yellow-800 font-semibold">Seats Locked for 10 Minutes</p>
                        <p class="text-yellow-700 text-sm">Please complete your booking within the time limit.</p>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">📝 Enter Passenger Information</h2>

                        <form method="POST" action="{{ route('passenger.booking.details.store', $schedule) }}">
                            @csrf

                            <!-- Passenger Name -->
                            <div class="mb-6">
                                <label for="passenger_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="passenger_name" 
                                       id="passenger_name" 
                                       value="{{ old('passenger_name', Auth::user()->name) }}"
                                       class="w-full px-4 py-3 border @error('passenger_name') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="e.g., John Doe"
                                       required>
                                @error('passenger_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Passenger Phone -->
                            <div class="mb-6">
                                <label for="passenger_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       name="passenger_phone" 
                                       id="passenger_phone" 
                                       value="{{ old('passenger_phone') }}"
                                       class="w-full px-4 py-3 border @error('passenger_phone') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="01712345678"
                                       pattern="^01[0-9]{9}$"
                                       required>
                                @error('passenger_phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Format: 01XXXXXXXXX (11 digits)</p>
                            </div>

                            <!-- Passenger Email -->
                            <div class="mb-6">
                                <label for="passenger_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-gray-400">(Optional)</span>
                                </label>
                                <input type="email" 
                                       name="passenger_email" 
                                       id="passenger_email" 
                                       value="{{ old('passenger_email', Auth::user()->email) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="example@email.com">
                                @error('passenger_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Booking Type -->
                            <div class="mb-8">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Booking Type <span class="text-red-500">*</span>
                                </label>
                                
                                <div class="space-y-3">
                                    <label class="flex items-start p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-green-500 transition">
                                        <input type="radio" 
                                               name="booking_type" 
                                               value="book" 
                                               class="mt-1 text-green-600 focus:ring-green-500"
                                               {{ old('booking_type', 'book') === 'book' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <p class="font-semibold text-gray-800">📝 Book Now (Pay Later)</p>
                                            <p class="text-sm text-gray-600">Reserve seats now, pay at counter before departure. Booking expires in 24 hours.</p>
                                        </div>
                                    </label>

                                    <label class="flex items-start p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-green-500 transition">
                                        <input type="radio" 
                                               name="booking_type" 
                                               value="direct_pay" 
                                               class="mt-1 text-green-600 focus:ring-green-500"
                                               {{ old('booking_type') === 'direct_pay' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <p class="font-semibold text-gray-800">💳 Direct Payment</p>
                                            <p class="text-sm text-gray-600">Pay now and confirm your booking instantly. (Online payment coming soon - currently pay at counter)</p>
                                        </div>
                                    </label>
                                </div>

                                @error('booking_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="w-full px-6 py-4 bg-green-600 text-white rounded-xl font-bold text-lg hover:bg-green-700 transition shadow-lg">
                                Continue to Confirmation →
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Booking Summary Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white sticky top-8">
                        <h3 class="text-xl font-bold mb-6">📋 Booking Summary</h3>
                        
                        <div class="space-y-3 mb-6 text-sm">
                            <div>
                                <p class="text-green-100">🚌 Bus Company</p>
                                <p class="font-bold">{{ $schedule->bus->company->name }}</p>
                            </div>
                            <div>
                                <p class="text-green-100">📍 Route</p>
                                <p class="font-bold">
                                    {{ $schedule->bus->route->sourceDistrict->name }} → 
                                    {{ $schedule->bus->route->destinationDistrict->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-green-100">📅 Date & Time</p>
                                <p class="font-bold">
                                    {{ \Carbon\Carbon::parse($schedule->journey_date)->format('d M Y') }}<br>
                                    {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-green-400 pt-4 mb-4">
                            <div class="flex justify-between mb-2">
                                <span>Selected Seats:</span>
                                <span class="font-bold">{{ $seats->pluck('seat_number')->implode(', ') }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Fare per Seat:</span>
                                <span class="font-bold">৳{{ number_format($schedule->base_fare, 2) }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Total Seats:</span>
                                <span class="font-bold">{{ $seats->count() }}</span>
                            </div>
                        </div>

                        <div class="border-t border-green-400 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg">Total Amount:</span>
                                <span class="text-2xl font-bold">৳{{ number_format($totalAmount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
