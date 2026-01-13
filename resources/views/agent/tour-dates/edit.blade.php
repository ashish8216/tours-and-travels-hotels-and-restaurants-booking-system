@extends('agent.layout')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Tour Date</h1>
                <p class="text-gray-600 mt-1">Update date for {{ $tour->title }}</p>
            </div>
            <a href="{{ route('agent.tours.dates.index', $tour) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Dates
            </a>
        </div>
    </div>

    <form action="{{ route('agent.tours.dates.update', [$tour, $date]) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')

        <!-- Date & Time -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Date & Time</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Date *</label>
                    <input type="date" name="date" value="{{ old('date', $date->date) }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Start Time (Optional)</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $date->start_time) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Leave empty for all-day tours</p>
                    @error('start_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Capacity -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Capacity</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Available Slots *</label>
                    <input type="number" name="available_slots" value="{{ old('available_slots', $date->available_slots) }}"
                        min="{{ $date->booked_slots }}" max="{{ $tour->max_people }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">
                        Currently booked: {{ $date->booked_slots }} slots<br>
                        Max: {{ $tour->max_people }} people
                    </p>
                    @error('available_slots')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Status</h2>
            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="available" {{ old('status', $date->status) === 'available' ? 'checked' : '' }} required
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">Available</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="cancelled" {{ old('status', $date->status) === 'cancelled' ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">Cancelled</span>
                </label>
            </div>
            @error('status')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Notes (Optional)</h2>
            <div>
                <textarea name="notes" rows="3" placeholder="Any special notes for this date..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $date->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Current Stats -->
        <div class="mb-8 bg-gray-50 rounded-lg p-4">
            <h3 class="font-medium text-gray-900 mb-2">Current Statistics</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="bg-white p-3 rounded">
                    <p class="text-gray-500">Booked Slots</p>
                    <p class="text-lg font-medium text-gray-900">{{ $date->booked_slots }}</p>
                </div>
                <div class="bg-white p-3 rounded">
                    <p class="text-gray-500">Available Slots</p>
                    <p class="text-lg font-medium text-gray-900">{{ $date->available_slots }}</p>
                </div>
                <div class="bg-white p-3 rounded">
                    <p class="text-gray-500">Remaining Slots</p>
                    <p class="text-lg font-medium text-gray-900">{{ $date->available_slots - $date->booked_slots }}</p>
                </div>
                <div class="bg-white p-3 rounded">
                    <p class="text-gray-500">Status</p>
                    <p class="text-lg font-medium {{ $date->status === 'available' ? 'text-green-600' : 'text-red-600' }}">
                        {{ ucfirst($date->status) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Update Date
            </button>
            <a href="{{ route('agent.tours.dates.index', $tour) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
            @if($date->bookings()->whereIn('status', ['pending', 'confirmed'])->count() === 0)
                <button type="button" onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                    Delete Date
                </button>
            @endif
        </div>
    </form>

    <!-- Delete Form -->
    <form id="delete-form" action="{{ route('agent.tours.dates.destroy', [$tour, $date]) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this date? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
