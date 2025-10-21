<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - {{ $booking->booking_reference }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">🎫 Booking Details</h1>
                        <p class="text-green-100 text-sm mt-1">Booking Reference: {{ $booking->booking_reference }}</p>
                    </div>
                    <a href="{{ route('passenger.bookings.index') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition font-semibold">
                        ← My Bookings
                    </a>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Status Badge -->
            <div class="mb-6 text-center">
                @if($booking->status === 'confirmed')
                    <span class="inline-block px-8 py-3 bg-green-100 text-green-700 rounded-full font-bold text-lg">
                        ✅ Paid
                    </span>
                    
                    <!-- PROMINENT PDF DOWNLOAD BUTTON -->
                    <div class="mt-4 mb-4">
                        <a href="{{ route('passenger.booking.invoice', $booking) }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-black rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Ticket PDF
                        </a>
                    </div>
                @elseif($booking->status === 'pending')
                    <span class="inline-block px-8 py-3 bg-orange-100 text-orange-700 rounded-full font-bold text-lg">
                        💳 Unpaid
                    </span>
                    @if($booking->payment_deadline)
                        @php
                            $minutesRemaining = now()->diffInMinutes($booking->payment_deadline, false);
                            $isExpired = $minutesRemaining <= 0;
                        @endphp
                        
                        @if($isExpired)
                            <div class="mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded max-w-2xl mx-auto">
                                <p class="text-red-800 font-semibold">⏰ Payment deadline has passed!</p>
                                <p class="text-red-700 text-sm">This booking will be auto-released shortly.</p>
                            </div>
                        @else
                            <div class="mt-4 bg-orange-50 border-l-4 border-orange-500 p-4 rounded max-w-2xl mx-auto">
                                <p class="text-orange-800 font-semibold">⏳ Complete payment within {{ $minutesRemaining }} minute(s)</p>
                                <p class="text-orange-700 text-sm">Payment deadline: {{ $booking->payment_deadline->format('h:i A, d M Y') }}</p>
                            </div>
                            <a href="{{ route('passenger.booking.payment', $booking) }}" 
                               class="inline-block mt-4 px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                                💳 Pay Now
                            </a>
                        @endif
                    @endif
                @elseif($booking->status === 'cancelled')
                    <span class="inline-block px-8 py-3 bg-red-100 text-red-700 rounded-full font-bold text-lg">
                        ❌ Cancelled
                    </span>
                @elseif($booking->status === 'expired')
                    <span class="inline-block px-8 py-3 bg-gray-100 text-gray-700 rounded-full font-bold text-lg">
                        ⏰ Cancelled
                    </span>
                @else
                    <span class="inline-block px-8 py-3 bg-gray-100 text-gray-700 rounded-full font-bold text-lg">
                        {{ ucfirst($booking->status) }}
                    </span>
                @endif
            </div>

            <!-- Booking Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-green-500 mb-6">
                <!-- Journey Details -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">🚌 Journey Information</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Bus Company</p>
                            <p class="font-bold text-gray-800">{{ $booking->busSchedule->bus->company->name }}</p>
                            <p class="text-sm text-gray-600">{{ $booking->busSchedule->bus->bus_number }} • {{ $booking->busSchedule->bus->bus_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Route</p>
                            <p class="font-bold text-gray-800">
                                {{ $booking->busSchedule->bus->route->sourceDistrict->name }} → 
                                {{ $booking->busSchedule->bus->route->destinationDistrict->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Journey Date</p>
                            <p class="font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($booking->busSchedule->journey_date)->format('l, d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Departure Time</p>
                            <p class="font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($booking->busSchedule->departure_time)->format('h:i A') }}
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
                            <p class="font-bold text-gray-800">{{ $booking->passenger_details['name'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Phone Number</p>
                            <p class="font-bold text-gray-800">{{ $booking->passenger_details['phone'] ?? 'N/A' }}</p>
                        </div>
                        @if(isset($booking->passenger_details['email']) && !empty($booking->passenger_details['email']))
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Email Address</p>
                            <p class="font-bold text-gray-800">{{ $booking->passenger_details['email'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Seat & Payment Details -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">🪑 Seat Details</h2>
                    <div class="flex gap-2 mb-4">
                        @foreach($booking->seats as $seat)
                            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg font-bold">
                                {{ $seat->seat_number }}
                            </span>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-600">Total Seats: <span class="font-bold text-gray-800">{{ $booking->total_seats }}</span></p>
                </div>

                <!-- Fare Breakdown -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">💰 Payment Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booking Type:</span>
                            <span class="font-semibold text-gray-800">
                                @if($booking->booking_type === 'book')
                                    📝 Book Now (Pay Later)
                                @else
                                    💳 Direct Payment
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fare per Seat:</span>
                            <span class="font-semibold text-gray-800">৳{{ number_format($booking->busSchedule->fare, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Number of Seats:</span>
                            <span class="font-semibold text-gray-800">{{ $booking->total_seats }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t-2 border-gray-300">
                            <span class="text-lg font-bold text-gray-800">Total Amount:</span>
                            <span class="text-2xl font-bold text-green-600">৳{{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($booking->canBeCancelled())
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">⚠️ Cancel Booking</h3>
                    <p class="text-gray-600 mb-4">If you wish to cancel this booking, click the button below. Your seats will be released.</p>
                    <form method="POST" 
                          action="{{ route('passenger.bookings.cancel', $booking) }}" 
                          onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                        @csrf
                        <button type="submit" 
                                class="px-8 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition shadow-lg">
                            Cancel Booking
                        </button>
                    </form>
                </div>
            @endif

            <!-- Important Notes -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-bold text-blue-800 mb-3">📌 Important Notes:</h3>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li>• Please arrive at the boarding point at least <strong>30 minutes before</strong> departure.</li>
                    <li>• Carry a valid <strong>government-issued ID</strong> for verification.</li>
                    @if($booking->status === 'pending')
                        <li>• Please complete payment at the counter <strong>before departure</strong>.</li>
                        <li>• Your booking will expire after <strong>24 hours</strong> if payment is not completed.</li>
                    @endif
                    <li>• For any assistance, contact the bus company directly.</li>
                </ul>
            </div>


        </main>
    </div>
</body>
</html>
