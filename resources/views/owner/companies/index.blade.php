<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Companies - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Modern Header -->
        <header class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">🏢 My Companies</h1>
                        <p class="text-green-100 text-sm mt-1">Manage your bus companies</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('owner.dashboard') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition font-semibold">
                            ← Dashboard
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

        <!-- Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">✓</span>
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Action Bar -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">All Companies</h2>
                        <p class="text-gray-500 text-sm mt-1">Total: {{ $companies->total() }} companies</p>
                    </div>
                    <a href="{{ route('owner.companies.create') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-green-700 transition font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        + Add New Company
                    </a>
                </div>
            </div>

            <!-- Companies Grid -->
            @if($companies->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($companies as $company)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition border border-gray-100 overflow-hidden group">
                        <!-- Company Header -->
                        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-full p-3">
                                    <span class="text-3xl font-bold">{{ substr($company->name, 0, 1) }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    @if($company->is_verified)
                                        <span class="px-3 py-1 bg-blue-500 bg-opacity-80 backdrop-blur-sm rounded-full text-xs font-semibold">
                                            ✓ Verified
                                        </span>
                                    @endif
                                    @if($company->is_active)
                                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-semibold">
                                            ● Active
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-500 bg-opacity-80 backdrop-blur-sm rounded-full text-xs font-semibold">
                                            ✗ Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ $company->name }}</h3>
                            <p class="text-green-100 text-sm">License: {{ $company->license_number }}</p>
                        </div>

                        <!-- Company Details -->
                        <div class="p-6 space-y-3">
                            <div class="flex items-start">
                                <span class="text-gray-400 mr-3 mt-1">📧</span>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="text-sm text-gray-800 font-medium">{{ $company->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-gray-400 mr-3 mt-1">📱</span>
                                <div>
                                    <p class="text-xs text-gray-500">Phone</p>
                                    <p class="text-sm text-gray-800 font-medium">{{ $company->phone }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-gray-400 mr-3 mt-1">📍</span>
                                <div>
                                    <p class="text-xs text-gray-500">Address</p>
                                    <p class="text-sm text-gray-800">{{ Str::limit($company->address, 40) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-6 pb-6 flex gap-2">
                            <a href="{{ route('owner.companies.edit', $company) }}" class="flex-1 bg-green-500 text-white text-center py-2 px-4 rounded-lg hover:bg-green-600 transition font-semibold text-sm">
                                ✏️ Edit
                            </a>
                            <form method="POST" action="{{ route('owner.companies.destroy', $company) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this company?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition font-semibold text-sm">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                    {{ $companies->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-md p-12 text-center border border-gray-100">
                    <div class="text-7xl mb-4">🏢</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No Companies Yet</h3>
                    <p class="text-gray-500 mb-6">Start by creating your first bus company</p>
                    <a href="{{ route('owner.companies.create') }}" class="inline-block bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 rounded-xl hover:from-green-600 hover:to-green-700 transition font-semibold shadow-lg">
                        + Create Your First Company
                    </a>
                </div>
            @endif
        </main>
    </div>
</body>
</html>
