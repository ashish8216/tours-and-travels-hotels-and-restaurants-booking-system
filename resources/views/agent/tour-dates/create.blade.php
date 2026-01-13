@extends('agent.layout')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add Tour Date</h1>
                <p class="text-gray-600 mt-1">Add a new date for {{ $tour->title }}</p>
            </div>
            <a href="{{ route('agent.tours.dates.index', $tour) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Dates
            </a>
        </div>
    </div>

    <form action="{{ route('agent.tours.dates.store', $tour) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <!-- Date & Time -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Date & Time</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Date *</label>
                    <input type="date" name="date" value="{{ old('date') }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Start Time (Optional)</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}"
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
                    <input type="number" name="available_slots" value="{{ old('available_slots', $tour->max_people) }}"
                        min="1" max="{{ $tour->max_people }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Max: {{ $tour->max_people }} people</p>
                    @error('available_slots')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Notes (Optional)</h2>
            <div>
                <textarea name="notes" rows="3" placeholder="Any special notes for this date..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Tour Info -->
        <div class="mb-8 bg-gray-50 rounded-lg p-4">
            <h3 class="font-medium text-gray-900 mb-2">Tour Information</h3>
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Tour:</span>
                    <span class="font-medium">{{ $tour->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Location:</span>
                    <span>{{ $tour->location }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Duration:</span>
                    <span>{{ $tour->duration }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Price:</span>
                    <span class="font-medium text-gray-900">${{ number_format($tour->price, 2) }} per person</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Add Date
            </button>
            <a href="{{ route('agent.tours.dates.index', $tour) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
