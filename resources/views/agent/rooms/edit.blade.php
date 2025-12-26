@extends('agent.layout')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('agent.rooms.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Back to Rooms
        </a>
        <h1 class="text-2xl font-semibold text-gray-800">Edit Room</h1>
        <p class="text-gray-600">Update room details for {{ $room->room_name }}</p>
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

    <form method="POST" action="{{ route('agent.rooms.update', $room) }}" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6">
        @csrf
        @method('PUT')

        <!-- Room Name -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Room Name</label>
            <input type="text"
                   name="room_name"
                   value="{{ old('room_name', $room->room_name) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                       value="{{ old('price_per_night', $room->price_per_night) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Maximum Guests</label>
                <input type="number"
                       min="1"
                       name="max_guests"
                       value="{{ old('max_guests', $room->max_guests) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>
        </div>

        <!-- Existing Images -->
        @if($room->images->count() > 0)
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Current Images</label>
            <div class="flex flex-wrap gap-3 mb-3">
                @foreach($room->images as $image)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $image->image_path) }}"
                         class="w-24 h-24 object-cover rounded-lg border {{ $image->is_primary ? 'border-blue-500 border-2' : 'border-gray-300' }}">
                    @if($image->is_primary)
                    <span class="absolute top-0 left-0 bg-blue-600 text-white text-xs px-2 py-1 rounded-br">Primary</span>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                        <div class="flex space-x-1">
                            @if(!$image->is_primary)
                            <form action="{{ route('agent.rooms.images.set-primary', [$room, $image]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="p-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                        title="Set as primary">
                                    <i class="fas fa-star text-xs"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('agent.rooms.images.destroy', [$room, $image]) }}" method="POST"
                                  onsubmit="return confirm('Delete this image?');"
                                  class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1 bg-red-600 text-white rounded hover:bg-red-700"
                                        title="Delete image">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Add More Images -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Add More Images</label>
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
                    <p class="text-gray-600">Click to upload more images</p>
                    <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF up to 2MB each</p>
                </label>
            </div>
        </div>

        <!-- Facilities -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Facilities</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <label class="flex items-center">
                    <input type="checkbox" name="ac" value="1" class="mr-2"
                           {{ old('ac', $room->ac) ? 'checked' : '' }}>
                    <span class="text-gray-700">AC</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="tv" value="1" class="mr-2"
                           {{ old('tv', $room->tv) ? 'checked' : '' }}>
                    <span class="text-gray-700">TV</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="breakfast" value="1" class="mr-2"
                           {{ old('breakfast', $room->breakfast) ? 'checked' : '' }}>
                    <span class="text-gray-700">Breakfast</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="attached_bathroom" value="1" class="mr-2"
                           {{ old('attached_bathroom', $room->attached_bathroom) ? 'checked' : '' }}>
                    <span class="text-gray-700">Attached Bath</span>
                </label>
            </div>
        </div>

        <!-- Availability -->
        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Availability</label>
            <select name="availability"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="available" {{ old('availability', $room->availability) == 'available' ? 'selected' : '' }}>
                    Available
                </option>
                <option value="not_available" {{ old('availability', $room->availability) == 'not_available' ? 'selected' : '' }}>
                    Not Available
                </option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <form action="{{ route('agent.rooms.destroy', $room) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this room and all its images?');"
                  class="inline">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-trash mr-2"></i> Delete Room
                </button>
            </form>

            <div class="flex items-center space-x-3">
                <a href="{{ route('agent.rooms.index') }}"
                   class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i> Update Room
                </button>
            </div>
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
