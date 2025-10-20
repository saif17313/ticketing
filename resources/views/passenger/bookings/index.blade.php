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
                                                ✅ Confirmed
                                            </span>
                                        @elseif($booking->status === 'pending')
                                            <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full font-bold text-sm">
                                                ⏳ Pending
                                            </span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="px-4 py-2 bg-red-100 text-red-700 rounded-full font-bold text-sm">
                                                ❌ Cancelled
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-bold text-sm">
                                                ⏰ Expired
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-4 gap-6 mb-6">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">📍 Route</p>
                                        <p class="font-bold text-gray-800">
                                            {{ $booking->busSchedule->bus->route->districts->first()->name }} → 
                                            {{ $booking->busSchedule->bus->route->districts->last()->name }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">📅 Journey Date</p>
                                        <p class="font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($booking->busSchedule->departure_date)->format('d M Y') }}
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

                                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                    <div>
                                        <p class="text-sm text-gray-600">Total Amount</p>
                                        <p class="text-2xl font-bold text-green-600">৳{{ number_format($booking->total_amount, 2) }}</p>
                                    </div>
                                    <div class="flex gap-3">
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
