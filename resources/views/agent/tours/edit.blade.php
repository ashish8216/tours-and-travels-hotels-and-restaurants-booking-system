@extends('agent.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Tour</h1>
                <p class="text-gray-600 mt-1">Update tour package details</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $tour->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($tour->status) }}
                </span>
            </div>
        </div>
    </div>

    <form action="{{ route('agent.tours.update', $tour) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Basic Information</h2>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Tour Title *</label>
                <input type="text" name="title" value="{{ old('title', $tour->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Description *</label>
                <textarea name="description" rows="5" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $tour->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Location *</label>
                    <input type="text" name="location" value="{{ old('location', $tour->location) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('location')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Difficulty Level</label>
                    <select name="difficulty_level"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Level</option>
                        <option value="Easy" {{ (old('difficulty_level', $tour->difficulty_level) === 'Easy') ? 'selected' : '' }}>Easy</option>
                        <option value="Moderate" {{ (old('difficulty_level', $tour->difficulty_level) === 'Moderate') ? 'selected' : '' }}>Moderate</option>
                        <option value="Hard" {{ (old('difficulty_level', $tour->difficulty_level) === 'Hard') ? 'selected' : '' }}>Hard</option>
                    </select>
                    @error('difficulty_level')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pricing & Capacity -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Pricing & Capacity</h2>

            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Price (per person) *</label>
                    <input type="number" name="price" value="{{ old('price', $tour->price) }}" step="0.01" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('price')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Max People *</label>
                    <input type="number" name="max_people" value="{{ old('max_people', $tour->max_people) }}" min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('max_people')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Duration *</label>
                    <input type="text" name="duration" value="{{ old('duration', $tour->duration) }}" placeholder="e.g., 2 hours, Full day" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('duration')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700 font-medium mb-2">Duration Hours (Optional)</label>
                <input type="number" name="duration_hours" value="{{ old('duration_hours', $tour->duration_hours) }}" min="1"
                    class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('duration_hours')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Tour Details -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Tour Details</h2>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">What's Included</label>
                <textarea name="inclusions" rows="3" placeholder="Transportation, Meals, Guide, etc."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('inclusions', $tour->inclusions) }}</textarea>
                @error('inclusions')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">What's Not Included</label>
                <textarea name="exclusions" rows="3" placeholder="Personal expenses, Insurance, etc."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('exclusions', $tour->exclusions) }}</textarea>
                @error('exclusions')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Requirements</label>
                <textarea name="requirements" rows="3" placeholder="What guests should bring or know"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('requirements', $tour->requirements) }}</textarea>
                @error('requirements')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Images -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Images</h2>

            <!-- Current Main Image -->
            @if($tour->image)
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Current Main Image</label>
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('storage/' . $tour->image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-lg">
                        <div>
                            <p class="text-sm text-gray-600">Current image</p>
                            <a href="{{ asset('storage/' . $tour->image) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                                View full size
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Change Main Image</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Additional Images -->
            @if($tour->images && count($tour->images) > 0)
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Current Additional Images</label>
                    <div class="grid grid-cols-3 md:grid-cols-5 gap-4">
                        @foreach($tour->images as $index => $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image) }}" alt="Tour Image {{ $index + 1 }}" class="w-full h-24 object-cover rounded-lg">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Change Additional Images</label>
                <input type="file" name="images[]" accept="image/*" multiple
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Select new images to replace all current additional images</p>
                @error('images.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Status</h2>

            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="active" {{ old('status', $tour->status) === 'active' ? 'checked' : '' }} required
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">Active</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="inactive" {{ old('status', $tour->status) === 'inactive' ? 'checked' : '' }}
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
                Update Tour
            </button>
            <a href="{{ route('agent.tours.show', $tour) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
            <button type="button" onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Delete Tour
            </button>
        </div>
    </form>

    <!-- Delete Form -->
    <form id="delete-form" action="{{ route('agent.tours.destroy', $tour) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this tour? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
