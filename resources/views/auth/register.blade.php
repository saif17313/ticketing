<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-red-600">🚌 BD Bus Tickets</h1>
            <p class="text-gray-600 mt-2">Create your passenger account</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <!-- Full Name -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">
                    Full Name *
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror"
                    placeholder="Ahmed Hassan"
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Only letters and spaces (minimum 3 characters)</p>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">
                    Email Address *
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('email') border-red-500 @enderror"
                    placeholder="your@email.com"
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-medium mb-2">
                    Phone Number *
                </label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    required
                    maxlength="11"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('phone') border-red-500 @enderror"
                    placeholder="01712345678"
                >
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Bangladesh format: 01XXXXXXXXX (11 digits)</p>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">
                    Password *
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('password') border-red-500 @enderror"
                    placeholder="Minimum 8 characters"
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">At least 8 characters with letters and numbers</p>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">
                    Confirm Password *
                </label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="Re-enter password"
                >
            </div>

            <!-- Address (Optional) -->
            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-medium mb-2">
                    Address (Optional)
                </label>
                <textarea 
                    id="address" 
                    name="address" 
                    rows="2"
                    maxlength="500"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('address') border-red-500 @enderror"
                    placeholder="Dhanmondi, Dhaka-1209"
                >{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NID (Optional) -->
            <div class="mb-6">
                <label for="nid" class="block text-gray-700 font-medium mb-2">
                    NID Number (Optional)
                </label>
                <input 
                    type="text" 
                    id="nid" 
                    name="nid" 
                    value="{{ old('nid') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('nid') border-red-500 @enderror"
                    placeholder="Enter 10, 13, or 17 digit NID"
                >
                @error('nid')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Old: 10/13 digits, Smart: 17 digits</p>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition duration-200"
            >
                Create Account
            </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-red-600 font-medium hover:underline">Login here</a>
            </p>
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
