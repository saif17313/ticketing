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
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Edit Company</h1>
                        <p class="text-sm text-gray-600 mt-1">Update {{ $company->name }} information</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('owner.companies.index') }}" class="text-gray-600 hover:text-gray-900 font-medium"> Back</a>
                        <a href="{{ route('owner.dashboard') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">Dashboard</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm p-8 border border-gray-200">
                <form method="POST" action="{{ route('owner.companies.update', $company) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Company Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" class="w-full px-4 py-2.5 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Company Email <span class="text-red-600">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" class="w-full px-4 py-2.5 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-600">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" class="w-full px-4 py-2.5 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500">Format: 01XXXXXXXXX</p>
                    </div>
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address <span class="text-red-600">*</span></label>
                        <textarea name="address" id="address" rows="3" class="w-full px-4 py-2.5 border @error('address') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>{{ old('address', $company->address) }}</textarea>
                        @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">License Number <span class="text-red-600">*</span></label>
                        <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $company->license_number) }}" class="w-full px-4 py-2.5 border @error('license_number') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        @error('license_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }} class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Company is Active</span>
                        </label>
                        <p class="mt-1 ml-6 text-xs text-gray-500">Inactive companies won''t appear in search</p>
                    </div>
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('owner.companies.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">Update Company</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>