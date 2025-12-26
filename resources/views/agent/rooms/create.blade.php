@extends('agent.layout')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('agent.rooms.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Back to Rooms
        </a>
        <h1 class="text-2xl font-semibold text-gray-800">Add New Room</h1>
        <p class="text-gray-600">Create a new room listing for your property</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg border border-red-200">
            <ul class="list-disc pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('agent.rooms.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6">
        @csrf

        <!-- Room Name -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Room Name</label>
            <input type="text"
                   name="room_name"
                   value="{{ old('room_name') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="e.g., Deluxe Suite, Standard Room"
                   required>
        </div>

        <!-- Price & Guests -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-2">Price per Night (₹)</label>
                <input type="number"
                       step="0.01"
                       min="0"
                       name="price_per_night"
                       value="{{ old('price_per_night') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., 2500"
                       required>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Maximum Guests</label>
                <input type="number"
                       min="1"
                       name="max_guests"
                       value="{{ old('max_guests') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., 2"
                       required>
            </div>
        </div>

        <!-- Room Images -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Room Images</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <input type="file"
                       name="images[]"
                       multiple
                       accept="image/*"
                       id="imageInput"
                       class="hidden">
                <div id="imagePreview" class="mb-4"></div>
                <label for="imageInput" class="cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-600">Click to upload images or drag and drop</p>
                    <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF up to 2MB each</p>
                </label>
            </div>
            <p class="text-sm text-gray-500 mt-2">Upload at least one image. First image will be the primary display image.</p>
        </div>

        <!-- Facilities -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Facilities</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <label class="flex items-center">
                    <input type="checkbox" name="ac" value="1" class="mr-2" {{ old('ac') ? 'checked' : '' }}>
                    <span class="text-gray-700">AC</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="tv" value="1" class="mr-2" {{ old('tv') ? 'checked' : '' }}>
                    <span class="text-gray-700">TV</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="breakfast" value="1" class="mr-2" {{ old('breakfast') ? 'checked' : '' }}>
                    <span class="text-gray-700">Breakfast</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="attached_bathroom" value="1" class="mr-2" {{ old('attached_bathroom') ? 'checked' : '' }}>
                    <span class="text-gray-700">Attached Bath</span>
                </label>
            </div>
        </div>

        <!-- Availability -->
        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Availability</label>
            <select name="availability"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="available" {{ old('availability', 'available') == 'available' ? 'selected' : '' }}>
                    Available
                </option>
                <option value="not_available" {{ old('availability') == 'not_available' ? 'selected' : '' }}>
                    Not Available
                </option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('agent.rooms.index') }}"
               class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <i class="fas fa-save mr-2"></i> Save Room
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function(e) {
        imagePreview.innerHTML = '';

        if (this.files && this.files.length > 0) {
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'inline-block m-2';
                    div.innerHTML = `
                        <div class="relative">
                            <img src="${e.target.result}" class="w-24 h-24 object-cover rounded-lg border">
                            <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">${i+1}</span>
                        </div>
                    `;
                    imagePreview.appendChild(div);
                }

                reader.readAsDataURL(file);
            }
        }
    });
});
</script>
@endsection
