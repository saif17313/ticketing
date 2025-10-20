<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Buses - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">My Buses</h1>
                        <p class="text-sm text-gray-600 mt-1">Manage your fleet</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('owner.dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium"> Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r">
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            @endif
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">All Buses</h2>
                        <p class="text-sm text-gray-600 mt-1">Total: {{ $buses->total() }} buses</p>
                    </div>
                    <a href="{{ route('owner.buses.create') }}" class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 transition font-medium">+ Add New Bus</a>
                </div>
            </div>
            @if($buses->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seats</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($buses as $bus)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $bus->bus_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $bus->bus_model }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $bus->company->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $bus->route->sourceDistrict->name }}  {{ $bus->route->destinationDistrict->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded {{ $bus->bus_type == 'AC' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $bus->bus_type }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $bus->total_seats }} ({{ $bus->seat_layout }})</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($bus->is_active)
                                            <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('owner.buses.edit', $bus) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                                        <form method="POST" action="{{ route('owner.buses.destroy', $bus) }}" class="inline" onsubmit="return confirm('Delete this bus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">{{ $buses->links() }}</div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
                    <div class="text-5xl mb-4"></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Buses Yet</h3>
                    <p class="text-gray-600 mb-6">Start by adding your first bus</p>
                    <a href="{{ route('owner.buses.create') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-medium">+ Add First Bus</a>
                </div>
            @endif
        </main>
    </div>
</body>
</html>