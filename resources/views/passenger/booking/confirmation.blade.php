<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">✅ Confirm Your Booking</h1>
                    <a href="{{ route('passenger.booking.details', $schedule) }}" class="text-green-600 hover:text-green-700 font-semibold">← Edit Details</a>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success Message -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">🎉</span>
                    <div>
                        <p class="text-green-800 font-semibold">Almost Done!</p>
                        <p class="text-green-700 text-sm">Please review your booking details before confirming.</p>
                    </div>
                </div>
            </div>

            <!-- Confirmation Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-green-500 mb-6">
                <!-- Journey Details -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">🚌 Journey Details</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Bus Company</p>
                            <p class="font-bold text-gray-800">{{ $schedule->bus->company->name }}</p>
                            <p class="text-sm text-gray-600">{{ $schedule->bus->bus_number }} • {{ $schedule->bus->bus_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Route</p>
                            <p class="font-bold text-gray-800">
                                {{ $schedule->bus->route->sourceDistrict->name }} → 
                                {{ $schedule->bus->route->destinationDistrict->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Journey Date</p>
                            <p class="font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($schedule->departure_date)->format('l, d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Departure Time</p>
                            <p class="font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Passenger Details -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">👤 Passenger Information</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Full Name</p>
                            <p class="font-bold text-gray-800">{{ $passengerDetails['name'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Phone Number</p>
                            <p class="font-bold text-gray-800">{{ $passengerDetails['phone'] }}</p>
                        </div>
                        @if(!empty($passengerDetails['email']))
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Email Address</p>
                            <p class="font-bold text-gray-800">{{ $passengerDetails['email'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Seat & Payment Details -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">🪑 Seat & Payment Details</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Selected Seats</p>
                            <div class="flex gap-2 mt-2">
                                @foreach($seats as $seat)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg font-bold text-sm">
                                        {{ $seat->seat_number }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Booking Type</p>
                            <p class="font-bold text-gray-800">
                                @if($bookingType === 'book')
                                    📝 Book Now (Pay Later)
                                @else
                                    💳 Direct Payment
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Fare Breakdown -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">💰 Fare Breakdown</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fare per Seat:</span>
                            <span class="font-semibold text-gray-800">৳{{ number_format($schedule->fare, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Number of Seats:</span>
                            <span class="font-semibold text-gray-800">{{ $seats->count() }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t-2 border-gray-300">
                            <span class="text-lg font-bold text-gray-800">Total Amount:</span>
                            <span class="text-2xl font-bold text-green-600">৳{{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
                <h3 class="font-bold text-yellow-800 mb-3">📌 Important Information:</h3>
                <ul class="space-y-2 text-sm text-yellow-700">
                    @if($bookingType === 'book')
                        <li>• Your booking will be valid for <strong>24 hours</strong>. Please complete payment at the counter.</li>
                        <li>• Unpaid bookings will be automatically cancelled after 24 hours.</li>
                    @else
                        <li>• Your booking will be <strong>confirmed immediately</strong> upon completion.</li>
                        <li>• Please pay at the counter before departure.</li>
                    @endif
                    <li>• Please arrive at least <strong>30 minutes before</strong> departure.</li>
                    <li>• Carry a valid government-issued ID for verification.</li>
                    <li>• You can view and manage your bookings from "My Bookings" page.</li>
                </ul>
            </div>

            <!-- Confirm Form -->
            <form method="POST" action="{{ route('passenger.booking.confirm', $schedule) }}">
                @csrf
                
                <div class="flex items-center justify-center mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" required class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                        <span class="ml-3 text-gray-700">
                            I agree to the <a href="#" class="text-green-600 hover:underline">terms and conditions</a>
                        </span>
                    </label>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('passenger.booking.details', $schedule) }}" 
                       class="flex-1 px-6 py-4 border-2 border-gray-300 rounded-xl text-center text-gray-700 font-bold hover:bg-gray-50 transition">
                        ← Go Back
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-bold text-lg hover:from-green-600 hover:to-green-700 transition shadow-lg">
                        🎉 Confirm Booking
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
