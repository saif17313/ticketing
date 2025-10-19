<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <!-- Wrap everything in one Alpine component -->
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8" x-data="{ role: '{{ old('role', 'passenger') }}' }">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-red-600">🚌 BD Bus Tickets</h1>
            <p class="text-gray-600 mt-2">Login to your account</p>
        </div>

        <!-- Toggle Button for Passenger/Owner -->
        <div class="flex bg-gray-200 rounded-lg p-1 mb-6">
            <button 
                type="button"
                @click="role = 'passenger'"
                :class="role === 'passenger' ? 'bg-white text-red-600 shadow' : 'text-gray-600'"
                class="flex-1 py-2 px-4 rounded-md font-medium transition-all duration-200"
            >
                👤 Passenger
            </button>
            <button 
                type="button"
                @click="role = 'owner'"
                :class="role === 'owner' ? 'bg-white text-red-600 shadow' : 'text-gray-600'"
                class="flex-1 py-2 px-4 rounded-md font-medium transition-all duration-200"
            >
                🏢 Owner
            </button>
            
            <!-- Hidden input to store selected role -->
            <input type="hidden" name="role" :value="role" form="loginForm">
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="your@email.com"
                >
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="••••••••"
                >
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="text-sm text-red-600 hover:underline">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition duration-200"
            >
                Login
            </button>
        </form>

        <!-- Register Link (only for passengers) -->
        <div class="mt-6 text-center">
            <template x-if="role === 'passenger'">
                <p class="text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-red-600 font-medium hover:underline">Register here</a>
                </p>
            </template>
            <template x-if="role === 'owner'">
                <p class="text-gray-600 text-sm">
                    Owner accounts are created by admin only.
                </p>
            </template>
        </div>

        <!-- Back to Home -->
        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Back to Home
            </a>
        </div>
    </div>
</body>
</html>
