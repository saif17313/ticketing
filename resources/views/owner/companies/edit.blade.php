<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company - BD Bus Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white"> Edit Company</h1>
                        <p class="text-green-100 text-sm mt-1">Update {{ $company->name }} information</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('owner.companies.index') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition font-semibold"> Back</a>
                        <a href="{{ route('owner.dashboard') }}" class="bg-green-800 text-white px-4 py-2 rounded-lg hover:bg-green-900 transition font-semibold">Dashboard</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Company Information</h2>
                    <p class="text-gray-600">Update the details below to modify your company</p>
                </div>
                <form method="POST" action="{{ route('owner.companies.update', $company) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2"> Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., Green Line Paribahan" required>
                        @error('name')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1"></span> {{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">Must be unique and max 150 characters</p>
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2"> Company Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., info@greenline.com" required>
                        @error('email')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1"></span> {{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2"> Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" class="w-full px-4 py-3 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., 01712345678" required>
                        @error('phone')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1"></span> {{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">Format: 01XXXXXXXXX (11 digits)</p>
                    </div>
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2"> Address <span class="text-red-500">*</span></label>
                        <textarea name="address" id="address" rows="3" class="w-full px-4 py-3 border @error('address') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., 123 Motijheel, Dhaka-1000" required>{{ old('address', $company->address) }}</textarea>
                        @error('address')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1"></span> {{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">Max 500 characters</p>
                    </div>
                    <div class="mb-6">
                        <label for="license_number" class="block text-sm font-semibold text-gray-700 mb-2"> License Number <span class="text-red-500">*</span></label>
                        <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $company->license_number) }}" class="w-full px-4 py-3 border @error('license_number') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" placeholder="e.g., LIC-2025-001" required>
                        @error('license_number')<p class="mt-2 text-sm text-red-600 flex items-center"><span class="mr-1"></span> {{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">Must be unique, max 50 characters</p>
                    </div>
                    <div class="mb-8 bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }} class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="ml-3 text-sm font-semibold text-gray-700"> Company is Active</span>
                        </label>
                        <p class="mt-2 ml-8 text-xs text-gray-500">Inactive companies won't appear in passenger search results</p>
                    </div>
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('owner.companies.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition font-semibold">Cancel</a>
                        <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5"> Update Company</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
