<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Options - {{ $booking->booking_reference }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">💳 Complete Payment</h1>
                    <a href="{{ route('passenger.bookings.show', $booking) }}" class="text-green-600 hover:text-green-700 font-semibold">← Back to Booking</a>
                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Expiry Warning -->
            @if($booking->expires_at)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">⏰</span>
                        <div>
                            <p class="text-yellow-800 font-semibold">Booking Expires Soon!</p>
                            <p class="text-yellow-700 text-sm">
                                Complete payment before: <strong>{{ $booking->expires_at->format('d M Y, h:i A') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Payment Methods -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">💰 Select Payment Method</h2>

                        <div class="space-y-4">
                            <!-- bKash -->
                            <button onclick="selectPaymentMethod('bkash')" 
                                    class="payment-method-card w-full flex items-center p-6 border-2 border-gray-200 rounded-xl hover:border-pink-500 hover:shadow-lg transition cursor-pointer">
                                <div class="flex-shrink-0 w-16 h-16 bg-pink-100 rounded-lg flex items-center justify-center">
                                    <span class="text-3xl">📱</span>
                                </div>
                                <div class="ml-4 flex-1 text-left">
                                    <h3 class="font-bold text-gray-800 text-lg">bKash</h3>
                                    <p class="text-sm text-gray-600">Pay with bKash mobile banking</p>
                                </div>
                                <div class="ml-4">
                                    <span class="text-pink-500 font-bold">→</span>
                                </div>
                            </button>

                            <!-- Nagad -->
                            <button onclick="selectPaymentMethod('nagad')" 
                                    class="payment-method-card w-full flex items-center p-6 border-2 border-gray-200 rounded-xl hover:border-orange-500 hover:shadow-lg transition cursor-pointer">
                                <div class="flex-shrink-0 w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <span class="text-3xl">💸</span>
                                </div>
                                <div class="ml-4 flex-1 text-left">
                                    <h3 class="font-bold text-gray-800 text-lg">Nagad</h3>
                                    <p class="text-sm text-gray-600">Pay with Nagad mobile banking</p>
                                </div>
                                <div class="ml-4">
                                    <span class="text-orange-500 font-bold">→</span>
                                </div>
                            </button>

                            <!-- Rocket -->
                            <button onclick="selectPaymentMethod('rocket')" 
                                    class="payment-method-card w-full flex items-center p-6 border-2 border-gray-200 rounded-xl hover:border-purple-500 hover:shadow-lg transition cursor-pointer">
                                <div class="flex-shrink-0 w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <span class="text-3xl">🚀</span>
                                </div>
                                <div class="ml-4 flex-1 text-left">
                                    <h3 class="font-bold text-gray-800 text-lg">Rocket</h3>
                                    <p class="text-sm text-gray-600">Pay with Dutch-Bangla Rocket</p>
                                </div>
                                <div class="ml-4">
                                    <span class="text-purple-500 font-bold">→</span>
                                </div>
                            </button>

                            <!-- Card Payment -->
                            <button onclick="selectPaymentMethod('card')" 
                                    class="payment-method-card w-full flex items-center p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:shadow-lg transition cursor-pointer">
                                <div class="flex-shrink-0 w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-3xl">💳</span>
                                </div>
                                <div class="ml-4 flex-1 text-left">
                                    <h3 class="font-bold text-gray-800 text-lg">Credit/Debit Card</h3>
                                    <p class="text-sm text-gray-600">Visa, Mastercard, Amex accepted</p>
                                </div>
                                <div class="ml-4">
                                    <span class="text-blue-500 font-bold">→</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white sticky top-8">
                        <h3 class="text-xl font-bold mb-6">📋 Booking Summary</h3>
                        
                        <div class="space-y-3 mb-6 text-sm">
                            <div>
                                <p class="text-green-100">Booking Reference</p>
                                <p class="font-bold">{{ $booking->booking_reference }}</p>
                            </div>
                            <div>
                                <p class="text-green-100">🚌 Bus Company</p>
                                <p class="font-bold">{{ $booking->busSchedule->bus->company->name }}</p>
                            </div>
                            <div>
                                <p class="text-green-100">📍 Route</p>
                                <p class="font-bold">
                                    {{ $booking->busSchedule->bus->route->sourceDistrict->name }} → 
                                    {{ $booking->busSchedule->bus->route->destinationDistrict->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-green-100">📅 Journey Date</p>
                                <p class="font-bold">
                                    {{ \Carbon\Carbon::parse($booking->busSchedule->departure_date)->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-green-100">🪑 Seats</p>
                                <p class="font-bold">{{ $booking->seats->pluck('seat_number')->implode(', ') }}</p>
                            </div>
                        </div>

                        <div class="border-t border-green-400 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg">Total Amount:</span>
                                <span class="text-3xl font-bold">৳{{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Payment Modal -->
            <div id="mobilePaymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                    <div class="text-center mb-6">
                        <div id="modalIcon" class="text-6xl mb-4"></div>
                        <h3 id="modalTitle" class="text-2xl font-bold text-gray-800 mb-2"></h3>
                        <p class="text-gray-600">Enter your details to complete payment</p>
                    </div>

                    <form method="POST" action="{{ route('passenger.payment.mobile', $booking) }}" id="mobilePaymentForm">
                        @csrf
                        <input type="hidden" name="payment_method" id="selectedMethod">

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Number</label>
                            <input type="text" 
                                   name="mobile_number" 
                                   placeholder="01712345678" 
                                   pattern="^01[0-9]{9}$"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">PIN</label>
                            <input type="password" 
                                   name="pin" 
                                   placeholder="Enter your PIN" 
                                   minlength="4"
                                   maxlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   required>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" 
                                    onclick="closeMobileModal()" 
                                    class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition font-semibold">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold">
                                Pay Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Payment Modal -->
            <div id="cardPaymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                    <div class="text-center mb-6">
                        <div class="text-6xl mb-4">💳</div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Card Payment</h3>
                        <p class="text-gray-600">Enter your card details</p>
                    </div>

                    <form method="POST" action="{{ route('passenger.payment.card', $booking) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Card Number</label>
                            <input type="text" 
                                   name="card_number" 
                                   placeholder="1234 5678 9012 3456" 
                                   minlength="13"
                                   maxlength="19"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cardholder Name</label>
                            <input type="text" 
                                   name="card_name" 
                                   placeholder="John Doe" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   required>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                                <input type="text" 
                                       name="expiry_month" 
                                       placeholder="MM" 
                                       pattern="[0-9]{2}"
                                       maxlength="2"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                                <input type="text" 
                                       name="expiry_year" 
                                       placeholder="YYYY" 
                                       pattern="[0-9]{4}"
                                       maxlength="4"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">CVV</label>
                                <input type="text" 
                                       name="cvv" 
                                       placeholder="123" 
                                       pattern="[0-9]{3}"
                                       maxlength="3"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       required>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" 
                                    onclick="closeCardModal()" 
                                    class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition font-semibold">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold">
                                Pay Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function selectPaymentMethod(method) {
            if (method === 'card') {
                document.getElementById('cardPaymentModal').classList.remove('hidden');
            } else {
                document.getElementById('selectedMethod').value = method;
                
                const icons = {
                    bkash: '📱',
                    nagad: '💸',
                    rocket: '🚀'
                };
                
                const titles = {
                    bkash: 'bKash Payment',
                    nagad: 'Nagad Payment',
                    rocket: 'Rocket Payment'
                };
                
                document.getElementById('modalIcon').textContent = icons[method];
                document.getElementById('modalTitle').textContent = titles[method];
                document.getElementById('mobilePaymentModal').classList.remove('hidden');
            }
        }

        function closeMobileModal() {
            document.getElementById('mobilePaymentModal').classList.add('hidden');
            document.getElementById('mobilePaymentForm').reset();
        }

        function closeCardModal() {
            document.getElementById('cardPaymentModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('mobilePaymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMobileModal();
            }
        });

        document.getElementById('cardPaymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCardModal();
            }
        });
    </script>
</body>
</html>
