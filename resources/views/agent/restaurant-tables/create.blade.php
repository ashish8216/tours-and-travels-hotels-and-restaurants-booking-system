@extends('agent.layout')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add New Table</h1>
                <p class="text-gray-600 mt-1">Add a table to {{ $restaurant->name }}</p>
            </div>
            <a href="{{ route('agent.restaurants.tables.index', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Tables
            </a>
        </div>
    </div>

    <form action="{{ route('agent.restaurants.tables.store', $restaurant) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <!-- Table Information -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Table Information</h2>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Table Number *</label>
                    <input type="text" name="table_number" value="{{ old('table_number') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g., T01, Table 1">
                    @error('table_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Table Name (Optional)</label>
                    <input type="text" name="table_name" value="{{ old('table_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g., Window View, VIP Table">
                    @error('table_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Capacity *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 4) }}" min="1" max="20" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('capacity')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Table Type *</label>
                    <select name="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="indoor" {{ old('type') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                        <option value="outdoor" {{ old('type') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                        <option value="private_room" {{ old('type') == 'private_room' ? 'selected' : '' }}>Private Room</option>
                        <option value="bar" {{ old('type') == 'bar' ? 'selected' : '' }}>Bar</option>
                    </select>
                    @error('type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Status *</label>
                <select name="status" required
                    class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Add Table
            </button>
            <a href="{{ route('agent.restaurants.tables.index', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
