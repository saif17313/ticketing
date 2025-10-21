<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful! - {{ $booking->booking_reference }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: #f0f;
            animation: confetti-fall 3s linear infinite;
        }
        
        @keyframes checkmark {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
        
        .checkmark {
            animation: checkmark 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <!-- Success Card -->
        <div class="max-w-2xl w-full">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-block w-32 h-32 bg-green-500 rounded-full flex items-center justify-center mb-6 animate-bounce">
                    <svg class="w-20 h-20 text-white checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">🎉 Payment Successful!</h1>
                <p class="text-xl text-gray-600">Your booking has been confirmed</p>
            </div>

            <!-- Booking Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 border-2 border-green-500">
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-600 mb-1">Booking Reference</p>
                    <p class="text-3xl font-bold text-green-600">{{ $booking->booking_reference }}</p>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">🚌 Bus Company</p>
                            <p class="font-bold text-gray-800">{{ $booking->busSchedule->bus->company->name }}</p>
                        </div>
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
                            <p class="font-bold text-gray-800">{{ $booking->seats->pluck('seat_number')->implode(', ') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">💰 Amount Paid</p>
                            <p class="font-bold text-green-600 text-xl">৳{{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                    </div>

                    @if($booking->payment)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 mb-1">💳 Payment Method</p>
                        <p class="font-bold text-gray-800">{{ ucfirst($booking->payment->payment_method) }}</p>
                        <p class="text-xs text-gray-600 mt-1">Transaction ID: {{ $booking->payment->transaction_id }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('passenger.bookings.show', $booking) }}" 
                   class="flex-1 px-6 py-4 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-bold text-center text-lg">
                    📋 View Booking Details
                </a>
                <a href="{{ route('passenger.bookings.index') }}" 
                   class="flex-1 px-6 py-4 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition font-bold text-center text-lg">
                    📝 My Bookings
                </a>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-bold text-blue-800 mb-3">📌 Important Information:</h3>
                <ul class="space-y-2 text-sm text-blue-700">
                    <li>• Your booking confirmation has been sent to your email.</li>
                    <li>• Please arrive at the boarding point at least <strong>30 minutes before</strong> departure.</li>
                    <li>• Carry a valid <strong>government-issued ID</strong> for verification.</li>
                    <li>• You can view and download your ticket from "My Bookings" section.</li>
                    <li>• For any assistance, contact the bus company directly.</li>
                </ul>
            </div>

            <!-- Home Button -->
            <div class="text-center mt-6">
                <a href="{{ route('passenger.dashboard') }}" class="text-green-600 hover:text-green-700 font-semibold">
                    ← Back to Homepage
                </a>
            </div>
        </div>
    </div>

    <script>
        // Create confetti animation
        function createConfetti() {
            const colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
            const confettiCount = 50;

            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 3 + 's';
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    document.body.appendChild(confetti);

                    setTimeout(() => {
                        confetti.remove();
                    }, 5000);
                }, i * 50);
            }
        }

        // Trigger confetti on load
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>
