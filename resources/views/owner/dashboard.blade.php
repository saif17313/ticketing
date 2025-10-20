<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Modern Header with Gradient -->
        <header class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">🚌 BD Bus Tickets</h1>
                        <p class="text-green-100 text-sm mt-1">Owner Dashboard</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right mr-4">
                            <p class="text-white font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-green-100 text-sm">Owner Account</p>
                        </div>
                        <a href="{{ route('home') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition font-semibold">Home</a>
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

        <!-- Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 mb-8 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                        <p class="text-gray-600">Manage your bus companies, routes, and schedules all in one place.</p>
                    </div>
                    <div class="hidden md:block text-6xl">🚍</div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Companies -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Companies</p>
                            <p class="text-4xl font-bold text-green-600">{{ $totalCompanies }}</p>
                            <p class="text-gray-400 text-xs mt-2">Active businesses</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Buses -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Buses</p>
                            <p class="text-4xl font-bold text-blue-600">{{ $totalBuses }}</p>
                            <p class="text-gray-400 text-xs mt-2">Fleet size</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Schedules -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Active Schedules</p>
                            <p class="text-4xl font-bold text-orange-600">{{ $totalSchedules }}</p>
                            <p class="text-gray-400 text-xs mt-2">Upcoming trips</p>
                        </div>
                        <div class="bg-orange-100 rounded-full p-4">
                            <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-600 rounded-lg px-3 py-1 text-sm mr-3">Quick Actions</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('owner.companies.create') }}" class="group bg-gradient-to-br from-green-500 to-green-600 text-white text-center py-4 px-6 rounded-xl hover:from-green-600 hover:to-green-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <div class="text-3xl mb-2">🏢</div>
                        <div class="font-semibold">Add Company</div>
                    </a>
                    <a href="{{ route('owner.buses.create') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white text-center py-4 px-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <div class="text-3xl mb-2">🚌</div>
                        <div class="font-semibold">Add Bus</div>
                    </a>
                    <a href="{{ route('owner.schedules.create') }}" class="group bg-gradient-to-br from-orange-500 to-orange-600 text-white text-center py-4 px-6 rounded-xl hover:from-orange-600 hover:to-orange-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <div class="text-3xl mb-2">📅</div>
                        <div class="font-semibold">Add Schedule</div>
                    </a>
                    <a href="{{ route('owner.companies.index') }}" class="group bg-gradient-to-br from-gray-500 to-gray-600 text-white text-center py-4 px-6 rounded-xl hover:from-gray-600 hover:to-gray-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                        <div class="text-3xl mb-2">📊</div>
                        <div class="font-semibold">View All</div>
                    </a>
                </div>
            </div>

            <!-- Recent Companies -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-green-100 text-green-600 rounded-lg px-3 py-1 text-sm mr-3">Recent Companies</span>
                    </h3>
                    <a href="{{ route('owner.companies.index') }}" class="text-green-600 hover:text-green-700 font-semibold text-sm">View All →</a>
                </div>
                @if($recentCompanies->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentCompanies as $company)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 font-bold text-lg">{{ substr($company->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $company->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $company->license_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $company->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $company->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($company->is_active)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                ✓ Active
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                ✗ Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('owner.companies.edit', $company) }}" class="text-green-600 hover:text-green-900 font-semibold">Edit →</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📋</div>
                        <p class="text-gray-500 text-lg mb-4">No companies yet</p>
                        <a href="{{ route('owner.companies.create') }}" class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition font-semibold">
                            + Create Your First Company
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
