<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-red-600">🏢 Owner Dashboard</h1>
                <div class="flex items-center gap-4">
                    <span class="text-gray-700">Welcome, {{ auth()->user()->name }}!</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Dashboard Overview</h2>
                <p class="text-gray-600">Owner dashboard features will be added in upcoming commits...</p>
                
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-100 p-6 rounded-lg">
                        <h3 class="font-semibold text-blue-800">My Companies</h3>
                        <p class="text-3xl font-bold text-blue-600 mt-2">0</p>
                    </div>
                    <div class="bg-green-100 p-6 rounded-lg">
                        <h3 class="font-semibold text-green-800">Total Buses</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">0</p>
                    </div>
                    <div class="bg-purple-100 p-6 rounded-lg">
                        <h3 class="font-semibold text-purple-800">Active Routes</h3>
                        <p class="text-3xl font-bold text-purple-600 mt-2">0</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
