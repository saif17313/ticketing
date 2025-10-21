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
                            {{ $schedule->bus->route->sourceDistrict->name }} → {{ $schedule->bus->route->destinationDistrict->name }}
                        </p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($schedule->journey_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">🕐 Departure</p>
                        <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</p>
                        <p class="text-sm text-gray-600">Fare: ৳{{ number_format($schedule->base_fare, 2) }} per seat</p>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Seat Map -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-800">
                                🪑 Select Seats (Max 4)
                                @if($schedule->bus->bus_type === 'AC')
                                    <span class="text-sm font-normal text-blue-600 ml-2">• AC Bus (2+1 Layout)</span>
                                @else
                                    <span class="text-sm font-normal text-gray-600 ml-2">• Non-AC Bus (2+2 Layout)</span>
                                @endif
                            </h2>
                            <div class="text-sm text-gray-600">
                                Available: <span class="font-bold text-green-600">{{ $availableSeats->count() }}</span>
                            </div>
                        </div>

                        <!-- Seat Legend -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border-2 border-blue-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                Seat Legend
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center gap-3 bg-white p-3 rounded-lg border-2 border-gray-200">
                                    <div class="w-12 h-12 bg-white border-2 border-gray-300 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">Available</p>
                                        <p class="text-xs text-gray-600">Click to select</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 bg-white p-3 rounded-lg border-2 border-green-200">
                                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center shadow-md ring-2 ring-green-300">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-green-700">Selected</p>
                                        <p class="text-xs text-green-600">Your choice</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 bg-white p-3 rounded-lg border-2 border-red-200">
                                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-red-700">Occupied</p>
                                        <p class="text-xs text-red-600">Not available</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seat Selection Form -->
                        <form method="POST" action="{{ route('passenger.booking.seats.store', $schedule) }}" id="seatForm">
                            @csrf

                            <!-- Bus Layout Container -->
                            <div class="bg-gray-100 rounded-2xl p-8 border-4 border-gray-300">
                                <!-- Driver Section -->
                                <div class="flex justify-end mb-6">
                                    <div class="bg-gray-700 rounded-full p-4 w-16 h-16 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>

                                @php
                                    $allSeats = $availableSeats->merge($bookedSeats)->merge($lockedSeats)->sortBy('seat_number');
                                    $seatsArray = $allSeats->values()->all(); // Use ->all() instead of ->toArray() to keep objects
                                    $totalSeats = count($seatsArray);
                                    $busType = $schedule->bus->bus_type;
                                    
                                    // Different layouts: 2x2 for Non-AC, 2x1 for AC
                                    if ($busType === 'Non-AC') {
                                        $seatsPerRow = 4; // 2 left + 2 right (aisle in between)
                                        $leftSeatsCount = 2;
                                        $rightSeatsCount = 2;
                                        $gridCols = 13; // 4 left + 3 aisle + 4 right + 2 spacing
                                    } else { // AC
                                        $seatsPerRow = 3; // 2 left + 1 right (aisle in between)
                                        $leftSeatsCount = 2;
                                        $rightSeatsCount = 1;
                                        $gridCols = 11; // 4 left + 3 aisle + 4 right
                                    }
                                @endphp

                                <!-- Seat Grid with Aisle -->
                                <div class="space-y-4">
                                    @for($i = 0; $i < $totalSeats; $i += $seatsPerRow)
                                        <div class="grid gap-2" style="grid-template-columns: repeat({{ $gridCols }}, minmax(0, 1fr));">
                                            <!-- Left side seats (2 seats) -->
                                            @for($j = 0; $j < $leftSeatsCount && ($i + $j) < $totalSeats; $j++)
                                                @php $seat = $seatsArray[$i + $j]; @endphp
                                                <div class="col-span-2">
                                                    @if($seat->isAvailable())
                                                        <input type="checkbox" 
                                                               name="seats[]" 
                                                               value="{{ $seat->id }}" 
                                                               id="seat_{{ $seat->id }}"
                                                               class="seat-checkbox hidden"
                                                               data-seat="{{ $seat->seat_number }}">
                                                        <label for="seat_{{ $seat->id }}" 
                                                               class="seat-label block w-full h-14 bg-white border-2 border-gray-300 hover:border-blue-400 hover:bg-blue-50 rounded-lg cursor-pointer transition flex flex-col items-center justify-center text-gray-700 font-bold shadow-sm hover:shadow-md">
                                                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                                            </svg>
                                                            <span class="text-xs mt-1">{{ $seat->seat_number }}</span>
                                                        </label>
                                                    @elseif($seat->isBooked())
                                                        <div class="block w-full h-14 bg-red-500 border-2 border-red-600 rounded-lg cursor-not-allowed flex flex-col items-center justify-center text-white font-bold shadow-md">
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                                            </svg>
                                                            <span class="text-xs mt-1">{{ $seat->seat_number }}</span>
                                                        </div>
                                                    @else
                                                        <div class="block w-full h-14 bg-yellow-500 rounded-lg cursor-not-allowed flex flex-col items-center justify-center text-white font-bold opacity-70">
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                                            </svg>
                                                            <span class="text-xs mt-1">{{ $seat->seat_number }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endfor

                                            <!-- Empty columns for visual spacing (aisle) -->
                                            <div class="col-span-3"></div>

                                            <!-- Right side seats (2 for Non-AC, 1 for AC) -->
                                            @for($j = $leftSeatsCount; $j < $seatsPerRow && ($i + $j) < $totalSeats; $j++)
                                                @php $seat = $seatsArray[$i + $j]; @endphp
                                                <div class="{{ $busType === 'Non-AC' ? 'col-span-2' : 'col-span-4' }}">
                                                    @if($seat->isAvailable())
                                                        <input type="checkbox" 
                                                               name="seats[]" 
                                                               value="{{ $seat->id }}" 
                                                               id="seat_{{ $seat->id }}"
                                                               class="seat-checkbox hidden"
                                                               data-seat="{{ $seat->seat_number }}">
                                                        <label for="seat_{{ $seat->id }}" 
                                                               class="seat-label block w-full h-14 bg-white border-2 border-gray-300 hover:border-blue-400 hover:bg-blue-50 rounded-lg cursor-pointer transition flex flex-col items-center justify-center text-gray-700 font-bold shadow-sm hover:shadow-md">
                                                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                                            </svg>
                                                            <span class="text-xs mt-1">{{ $seat->seat_number }}</span>
                                                        </label>
                                                    @elseif($seat->isBooked())
                                                        <div class="block w-full h-14 bg-red-500 border-2 border-red-600 rounded-lg cursor-not-allowed flex flex-col items-center justify-center text-white font-bold shadow-md">
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                                            </svg>
                                                            <span class="text-xs mt-1">{{ $seat->seat_number }}</span>
                                                        </div>
                                                    @else
                                                        <div class="block w-full h-14 bg-yellow-500 rounded-lg cursor-not-allowed flex flex-col items-center justify-center text-white font-bold opacity-70">
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                                            </svg>
                                                            <span class="text-xs mt-1">{{ $seat->seat_number }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endfor
                                        </div>
                                    @endfor
                                </div>
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
                                <span class="font-bold">{{ \Carbon\Carbon::parse($schedule->journey_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Departure:</span>
                                <span class="font-bold">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fare per Seat:</span>
                                <span class="font-bold">৳{{ number_format($schedule->base_fare, 2) }}</span>
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
        document.addEventListener('DOMContentLoaded', function() {
            const farePerSeat = parseFloat('{{ $schedule->base_fare }}');
            const checkboxes = document.querySelectorAll('.seat-checkbox');
            const submitBtn = document.getElementById('submitBtn');
            const selectedInfo = document.getElementById('selectedInfo');
            const selectedSeatsSpan = document.getElementById('selectedSeats');
            const totalAmountSpan = document.getElementById('totalAmount');
            const seatCountSpan = document.getElementById('seatCount');
            const sidebarTotalSpan = document.getElementById('sidebarTotal');

            console.log('DOM Loaded. Found checkboxes:', checkboxes.length);

            // Add event listeners to all checkboxes
            checkboxes.forEach(checkbox => {
                console.log('Setting up checkbox:', checkbox.id);
                
                checkbox.addEventListener('change', function(e) {
                    console.log('Checkbox changed:', this.id, this.checked);
                    updateSelection();
                });
                
                // Find and setup label
                const label = document.querySelector(`label[for="${checkbox.id}"]`);
                if (label) {
                    console.log('Found label for:', checkbox.id);
                    // Add direct click handler to ensure it works
                    label.addEventListener('click', function(e) {
                        console.log('Label clicked for:', checkbox.id);
                        // The default behavior should toggle the checkbox
                        // We just need to update after a tiny delay
                        setTimeout(() => {
                            console.log('After click, checked:', checkbox.checked);
                            updateSelection();
                        }, 50);
                    });
                } else {
                    console.error('No label found for:', checkbox.id);
                }
            });

            function updateSelection() {
                console.log('updateSelection called');
                const selected = Array.from(checkboxes).filter(cb => cb.checked);
                const count = selected.length;
                console.log('Selected count:', count);
                
                // Limit to 4 seats
                if (count > 4) {
                    alert('⚠️ You can select maximum 4 seats at a time.');
                    const lastChecked = selected[selected.length - 1];
                    lastChecked.checked = false;
                    updateSelection();
                    return;
                }

                const total = count * farePerSeat;

            // Update all seat label colors
            checkboxes.forEach(checkbox => {
                const label = document.querySelector(`label[for="${checkbox.id}"]`);
                if (label) {
                    if (checkbox.checked) {
                        // Selected state - green
                        label.classList.remove('bg-white', 'border-gray-300', 'hover:border-blue-400', 'hover:bg-blue-50', 'text-gray-700');
                        label.classList.add('bg-green-500', 'border-green-500', 'hover:bg-green-600', 'ring-4', 'ring-green-300', 'text-white');
                        // Update icon color
                        const svg = label.querySelector('svg');
                        if (svg) {
                            svg.classList.remove('text-gray-600');
                            svg.classList.add('text-white');
                        }
                    } else {
                        // Available state - white with border
                        label.classList.remove('bg-green-500', 'border-green-500', 'hover:bg-green-600', 'ring-4', 'ring-green-300', 'text-white');
                        label.classList.add('bg-white', 'border-gray-300', 'hover:border-blue-400', 'hover:bg-blue-50', 'text-gray-700');
                        // Update icon color
                        const svg = label.querySelector('svg');
                        if (svg) {
                            svg.classList.remove('text-white');
                            svg.classList.add('text-gray-600');
                        }
                    }
                }
            });                // Update display
                if (count > 0) {
                    selectedInfo.classList.remove('hidden');
                    submitBtn.disabled = false;

                    const seatNumbers = selected.map(cb => cb.getAttribute('data-seat'));

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

            // Initialize on page load
            updateSelection();
        });
    </script>
</body>
</html>
