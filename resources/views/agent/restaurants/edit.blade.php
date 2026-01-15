@extends('agent.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Restaurant</h1>
        <p class="text-gray-600 mt-1">Update restaurant details</p>
    </div>

    <form action="{{ route('agent.restaurants.update', $restaurant) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Basic Information</h2>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Restaurant Name *</label>
                <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $restaurant->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Cuisine Type</label>
                    <input type="text" name="cuisine_type" value="{{ old('cuisine_type', $restaurant->cuisine_type) }}" placeholder="Italian, Chinese, etc."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('cuisine_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Location *</label>
                    <input type="text" name="location" value="{{ old('location', $restaurant->location) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('location')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact & Hours -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Contact & Hours</h2>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $restaurant->phone) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $restaurant->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Opening Time *</label>
                    <input type="time" name="opening_time" value="{{ old('opening_time', $restaurant->opening_time->format('H:i')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('opening_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Closing Time *</label>
                    <input type="time" name="closing_time" value="{{ old('closing_time', $restaurant->closing_time->format('H:i')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('closing_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Capacity -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Capacity</h2>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Total Capacity (Seats) *</label>
                <input type="number" name="capacity" value="{{ old('capacity', $restaurant->capacity) }}" min="1" required
                    class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('capacity')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Image -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Restaurant Image</h2>
            @if($restaurant->image)
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Current Image</label>
                    <img src="{{ asset('storage/' . $restaurant->image) }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg mb-2">
                    <a href="{{ asset('storage/' . $restaurant->image) }}" target="_blank" class="text-sm text-blue-600 hover:underline">View full size</a>
                </div>
            @endif
            <div>
                <label class="block text-gray-700 font-medium mb-2">Change Image</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Status</h2>
            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="active" {{ old('status', $restaurant->status) === 'active' ? 'checked' : '' }} required
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">Active</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="inactive" {{ old('status', $restaurant->status) === 'inactive' ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">Inactive</span>
                </label>
            </div>
            @error('status')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Update Restaurant
            </button>
            <a href="{{ route('agent.restaurants.show', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
