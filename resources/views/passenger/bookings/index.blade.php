<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">🎫 My Bookings</h1>
                        <p class="text-green-100 text-sm mt-1">Manage all your bus ticket bookings</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('passenger.dashboard') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition font-semibold">
                            🏠 Home
                        </a>
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

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

            @if($bookings->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">🎫</div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">No Bookings Yet</h2>
                    <p class="text-gray-600 mb-6">You haven't made any bookings yet. Start your journey today!</p>
                    <a href="{{ route('passenger.dashboard') }}" 
                       class="inline-block px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-bold hover:from-green-600 hover:to-green-700 transition shadow-lg">
                        🔍 Search Buses
                    </a>
                </div>
            @else
                <!-- Bookings List -->
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            {{ $booking->busSchedule->bus->company->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Booking Ref: <span class="font-semibold text-gray-800">{{ $booking->booking_reference }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        @if($booking->status === 'confirmed')
                                            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full font-bold text-sm">
                                                ✅ Paid
                                            </span>
                                        @elseif($booking->status === 'pending')
                                            <span class="px-4 py-2 bg-orange-100 text-orange-700 rounded-full font-bold text-sm">
                                                💳 Unpaid
                                            </span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="px-4 py-2 bg-red-100 text-red-700 rounded-full font-bold text-sm">
                                                ❌ Cancelled
                                            </span>
                                        @elseif($booking->status === 'expired')
                                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-bold text-sm">
                                                ⏰ Cancelled
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-bold text-sm">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-4 gap-6 mb-6">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">📍 Route</p>
                                        <p class="font-bold text-gray-800">
                                            {{ $booking->busSchedule->bus->route->sourceDistrict->name }} → 
                                            {{ $booking->busSchedule->bus->route->destinationDistrict->name }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">📅 Journey Date</p>
                                        <p class="font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($booking->busSchedule->journey_date)->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">🕐 Departure</p>
                                        <p class="font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($booking->busSchedule->departure_time)->format('h:i A') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">🪑 Seats</p>
                                        <p class="font-bold text-gray-800">
                                            {{ $booking->seats->pluck('seat_number')->implode(', ') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-200">
                                    <!-- Payment Deadline Warning for Pending Bookings -->
                                    @if($booking->status === 'pending' && $booking->payment_deadline)
                                        @php
                                            $minutesRemaining = now()->diffInMinutes($booking->payment_deadline, false);
                                            $isExpiringSoon = $minutesRemaining > 0 && $minutesRemaining <= 30;
                                            $isExpired = $minutesRemaining <= 0;
                                        @endphp
                                        
                                        @if($isExpired)
                                            <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-4 rounded">
                                                <p class="text-red-800 font-semibold">⏰ Payment deadline has passed. This booking will be auto-released.</p>
                                            </div>
                                        @elseif($isExpiringSoon)
                                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-4 rounded">
                                                <p class="text-yellow-800 font-semibold">⏳ Payment deadline: {{ $minutesRemaining }} minute(s) remaining!</p>
                                                <p class="text-yellow-700 text-sm">Complete payment by {{ $booking->payment_deadline->format('h:i A') }}</p>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-600">Total Amount</p>
                                            <p class="text-2xl font-bold text-green-600">৳{{ number_format($booking->total_amount, 2) }}</p>
                                            @if($booking->status === 'pending' && $booking->payment_deadline && !$booking->isPaymentDeadlinePassed())
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Pay by: {{ $booking->payment_deadline->format('d M Y, h:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex gap-3">
                                            <!-- Pay Now Button for Pending Bookings -->
                                            @if($booking->needsPayment())
                                                <a href="{{ route('passenger.booking.payment', $booking) }}" 
                                                   class="px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition font-semibold shadow-md">
                                                    💳 Pay Now
                                                </a>
                                            @endif
                                            
                                            <!-- Download PDF Button for Confirmed Bookings -->
                                            @if($booking->status === 'confirmed')
                                                <a href="{{ route('passenger.booking.invoice', $booking) }}" 
                                                   class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-black rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-md">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                                    </svg>
                                                    Download PDF
                                                </a>
                                            @endif
                                            
                                            <a href="{{ route('passenger.bookings.show', $booking) }}" 
                                               class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                                                View Details
                                            </a>
                                            @if($booking->canBeCancelled())
                                                <form method="POST" 
                                                      action="{{ route('passenger.bookings.cancel', $booking) }}" 
                                                      onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="px-6 py-2 border-2 border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition font-semibold">
                                                        Cancel Booking
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        </main>
    </div>
</body>
</html>
