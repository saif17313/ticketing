<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-red-600">👤 My Profile</h1>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">Home</a>
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
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-6">Profile Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="font-medium text-gray-700">Name:</label>
                        <p class="text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Email:</label>
                        <p class="text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Phone:</label>
                        <p class="text-gray-900">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Address:</label>
                        <p class="text-gray-900">{{ auth()->user()->address ?? 'Not provided' }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="font-semibold text-lg mb-4">My Bookings</h3>
                    <p class="text-gray-600">Your booking history will appear here...</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
