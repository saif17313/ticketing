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
            <div class="bg-white rounded-xl shadow p-6 mb-6 border border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">All Companies</h2>
                        <p class="text-gray-500 text-sm mt-1">Total: {{ $companies->total() }} companies</p>
                    </div>
                    <a href="{{ route('owner.companies.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition font-semibold shadow-md hover:shadow-lg">
                        + Add New Company
                    </a>
                </div>
            </div>

            <!-- Companies Grid -->
            @if($companies->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($companies as $company)
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition border border-gray-200 overflow-hidden group">
                        <!-- Company Header -->
                        <div class="bg-gray-50 p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-green-100 rounded-full p-3">
                                    <span class="text-2xl font-bold text-green-700">{{ substr($company->name, 0, 1) }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    @if($company->is_verified)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                            ✓ Verified
                                        </span>
                                    @endif
                                    @if($company->is_active)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                            ● Active
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                            ✗ Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $company->name }}</h3>
                            <p class="text-gray-600 text-sm">License: {{ $company->license_number }}</p>
                        </div>

                        <!-- Company Details -->
                        <div class="p-6 space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="text-xl">📧</span>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium">Email</p>
                                    <p class="text-sm text-gray-800">{{ $company->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-xl">📱</span>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium">Phone</p>
                                    <p class="text-sm text-gray-800">{{ $company->phone }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-xl">📍</span>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium">Address</p>
                                    <p class="text-sm text-gray-800">{{ Str::limit($company->address, 40) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-6 pb-6 flex gap-3">
                            <a href="{{ route('owner.companies.edit', $company) }}" class="flex-1 bg-green-600 text-white text-center py-2.5 px-4 rounded-lg hover:bg-green-700 transition font-semibold text-sm shadow-sm">
                                ✏️ Edit
                            </a>
                            <form method="POST" action="{{ route('owner.companies.destroy', $company) }}" class="flex-1" onsubmit="return confirm('⚠️ Are you sure you want to delete this company? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-200 border border-gray-200 transition font-semibold text-sm">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    {{ $companies->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow p-12 text-center border border-gray-200">
                    <div class="text-7xl mb-4">🏢</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No Companies Yet</h3>
                    <p class="text-gray-500 mb-6">Start by creating your first bus company</p>
                    <a href="{{ route('owner.companies.create') }}" class="inline-block bg-green-600 text-white px-8 py-4 rounded-xl hover:bg-green-700 transition font-semibold shadow-lg">
                        + Create Your First Company
                    </a>
                </div>
            @endif
        </main>
    </div>
</body>
</html>
