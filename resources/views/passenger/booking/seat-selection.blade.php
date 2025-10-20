<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - {{ $schedule->bus->company->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">🎫 Select Your Seats</h1>
                    <a href="{{ route('search') }}" class="text-green-600 hover:text-green-700 font-semibold">← Back to Search</a>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Journey Info Card -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">🚌 Bus Company</p>
                        <p class="font-bold text-gray-800">{{ $schedule->bus->company->name }}</p>
                        <p class="text-sm text-gray-600">{{ $schedule->bus->bus_number }} • {{ $schedule->bus->bus_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">📍 Route</p>
                        <p class="font-bold text-gray-800">
                            {{ $schedule->bus->route->districts->first()->name }} → {{ $schedule->bus->route->districts->last()->name }}
                        </p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($schedule->departure_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">🕐 Departure</p>
                        <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</p>
                        <p class="text-sm text-gray-600">Fare: ৳{{ number_format($schedule->fare, 2) }} per seat</p>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Seat Map -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-800">🪑 Select Seats (Max 4)</h2>
                            <div class="text-sm text-gray-600">
                                Available: <span class="font-bold text-green-600">{{ $availableSeats->count() }}</span>
                            </div>
                        </div>

                        <!-- Seat Legend -->
                        <div class="flex gap-6 mb-8 pb-6 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-green-500 rounded-lg"></div>
                                <span class="text-sm text-gray-600">Available</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg"></div>
                                <span class="text-sm text-gray-600">Selected</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-red-400 rounded-lg"></div>
                                <span class="text-sm text-gray-600">Booked</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-yellow-400 rounded-lg"></div>
                                <span class="text-sm text-gray-600">Locked</span>
                            </div>
                        </div>

                        <!-- Seat Selection Form -->
                        <form method="POST" action="{{ route('passenger.booking.seats.store', $schedule) }}" id="seatForm">
                            @csrf

                            <!-- Driver Section -->
                            <div class="mb-8">
                                <div class="inline-block bg-gray-200 px-6 py-3 rounded-lg mb-4">
                                    <span class="font-semibold text-gray-700">🚗 Driver</span>
                                </div>
                            </div>

                            <!-- Seat Grid -->
                            <div class="grid grid-cols-4 gap-4">
                                @php
                                    $allSeats = $availableSeats->merge($bookedSeats)->merge($lockedSeats)->sortBy('seat_number');
                                @endphp

                                @foreach($allSeats as $seat)
                                    <div class="seat-wrapper">
                                        @if($seat->isAvailable())
                                            <input type="checkbox" 
                                                   name="seats[]" 
                                                   value="{{ $seat->id }}" 
                                                   id="seat_{{ $seat->id }}"
                                                   class="seat-checkbox hidden">
                                            <label for="seat_{{ $seat->id }}" 
                                                   class="seat-label block w-full h-16 bg-green-500 hover:bg-green-600 rounded-lg cursor-pointer transition flex items-center justify-center text-white font-bold text-sm shadow-md hover:shadow-lg transform hover:scale-105">
                                                {{ $seat->seat_number }}
                                            </label>
                                        @elseif($seat->isBooked())
                                            <div class="seat-booked block w-full h-16 bg-red-400 rounded-lg cursor-not-allowed flex items-center justify-center text-white font-bold text-sm opacity-60">
                                                {{ $seat->seat_number }}
                                            </div>
                                        @else
                                            <div class="seat-locked block w-full h-16 bg-yellow-400 rounded-lg cursor-not-allowed flex items-center justify-center text-white font-bold text-sm opacity-60">
                                                {{ $seat->seat_number }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @error('seats')
                                <p class="mt-4 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Selected Seats Info -->
                            <div id="selectedInfo" class="mt-8 p-4 bg-blue-50 rounded-xl hidden">
                                <p class="text-sm text-gray-700">
                                    Selected Seats: <span id="selectedSeats" class="font-bold text-blue-600"></span>
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    Total Amount: <span id="totalAmount" class="font-bold text-blue-600"></span>
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    id="submitBtn"
                                    disabled
                                    class="w-full mt-6 px-6 py-4 bg-green-600 text-white rounded-xl font-bold text-lg hover:bg-green-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed shadow-lg">
                                Continue to Passenger Details →
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Booking Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white sticky top-8">
                        <h3 class="text-xl font-bold mb-6">📋 Booking Summary</h3>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span>Journey Date:</span>
                                <span class="font-bold">{{ \Carbon\Carbon::parse($schedule->departure_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Departure:</span>
                                <span class="font-bold">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fare per Seat:</span>
                                <span class="font-bold">৳{{ number_format($schedule->fare, 2) }}</span>
                            </div>
                        </div>

                        <div class="border-t border-green-400 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg">Selected Seats:</span>
                                <span id="seatCount" class="text-2xl font-bold">0</span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-lg">Total:</span>
                                <span id="sidebarTotal" class="text-2xl font-bold">৳0.00</span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-white/20 rounded-lg">
                            <p class="text-sm">💡 <strong>Tip:</strong> Select up to 4 seats at a time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const farePerSeat = {{ $schedule->fare }};
        const checkboxes = document.querySelectorAll('.seat-checkbox');
        const submitBtn = document.getElementById('submitBtn');
        const selectedInfo = document.getElementById('selectedInfo');
        const selectedSeatsSpan = document.getElementById('selectedSeats');
        const totalAmountSpan = document.getElementById('totalAmount');
        const seatCountSpan = document.getElementById('seatCount');
        const sidebarTotalSpan = document.getElementById('sidebarTotal');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelection);
        });

        function updateSelection() {
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            const count = selected.length;
            const total = count * farePerSeat;

            // Update selected seat labels
            document.querySelectorAll('.seat-label').forEach(label => {
                const checkbox = label.previousElementSibling;
                if (checkbox.checked) {
                    label.classList.remove('bg-green-500', 'hover:bg-green-600');
                    label.classList.add('bg-blue-500', 'hover:bg-blue-600');
                } else {
                    label.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    label.classList.add('bg-green-500', 'hover:bg-green-600');
                }
            });

            // Limit to 4 seats
            if (count > 4) {
                alert('⚠️ You can select maximum 4 seats at a time.');
                checkboxes.forEach(cb => {
                    if (!selected.slice(0, 4).includes(cb)) {
                        cb.checked = false;
                    }
                });
                updateSelection();
                return;
            }

            // Update display
            if (count > 0) {
                selectedInfo.classList.remove('hidden');
                submitBtn.disabled = false;

                const seatNumbers = selected.map(cb => {
                    const label = cb.nextElementSibling;
                    return label.textContent.trim();
                });

                selectedSeatsSpan.textContent = seatNumbers.join(', ');
                totalAmountSpan.textContent = '৳' + total.toFixed(2);
                seatCountSpan.textContent = count;
                sidebarTotalSpan.textContent = '৳' + total.toFixed(2);
            } else {
                selectedInfo.classList.add('hidden');
                submitBtn.disabled = true;
                seatCountSpan.textContent = '0';
                sidebarTotalSpan.textContent = '৳0.00';
            }
        }
    </script>
</body>
</html>
