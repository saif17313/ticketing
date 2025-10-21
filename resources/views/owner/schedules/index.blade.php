<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedules - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">📅 My Schedules</h1>
                        <p class="text-green-100 text-sm mt-1">Manage your bus trip schedules</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('owner.dashboard') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition font-semibold">🏢 Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition font-semibold">🚪 Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-xl">
                    <p class="text-green-800 font-medium flex items-center"><span class="mr-2">✅</span> {{ session('success') }}</p>
                </div>
            @endif
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">📋 All Schedules</h2>
                        <p class="text-sm text-gray-600 mt-1">Total: {{ $schedules->total() }} schedules</p>
                    </div>
                    <a href="{{ route('owner.schedules.create') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-green-700 transition font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">➕ Add New Schedule</a>
                </div>
            </div>
            @if($schedules->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">📅 Date & Time</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">🚌 Bus & Route</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">💰 Fare</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">💺 Seats</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">📊 Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">⚙️ Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($schedules as $schedule)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $schedule->journey_date->format('d M Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ date('h:i A', strtotime($schedule->departure_time)) }} - {{ date('h:i A', strtotime($schedule->arrival_time)) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $schedule->bus->bus_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $schedule->bus->route->sourceDistrict->name }}  {{ $schedule->bus->route->destinationDistrict->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ number_format($schedule->base_fare, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $schedule->available_seats }}/{{ $schedule->bus->total_seats }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($schedule->status == 'scheduled')
                                            <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800">Scheduled</span>
                                        @elseif($schedule->status == 'departed')
                                            <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">Departed</span>
                                        @elseif($schedule->status == 'arrived')
                                            <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800">Arrived</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-800">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('owner.schedules.edit', $schedule) }}" class="text-green-600 hover:text-green-800 font-semibold mr-4">✏️ Edit</a>
                                        <form method="POST" action="{{ route('owner.schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('🗑️ Delete this schedule?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">🗑️ Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">{{ $schedules->links() }}</div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-16 text-center border border-gray-100">
                    <div class="text-7xl mb-4">📅</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No Schedules Yet</h3>
                    <p class="text-gray-600 mb-8 text-lg">Start by creating your first bus schedule</p>
                    <a href="{{ route('owner.schedules.create') }}" class="inline-block bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 rounded-xl hover:from-green-600 hover:to-green-700 font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition">➕ Add First Schedule</a>
                </div>
            @endif
        </main>
    </div>
</body>
</html>