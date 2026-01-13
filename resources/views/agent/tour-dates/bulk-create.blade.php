@extends('agent.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bulk Add Dates</h1>
                <p class="text-gray-600 mt-1">Add multiple dates for {{ $tour->title }}</p>
            </div>
            <a href="{{ route('agent.tours.dates.index', $tour) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Dates
            </a>
        </div>
    </div>

    <form action="{{ route('agent.tours.dates.bulk-store', $tour) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <!-- Date Range -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Date Range</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Start Date *</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('start_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">End Date *</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('end_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Days of Week -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Days of the Week *</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp
                @foreach($days as $day)
                    <label class="flex items-center cursor-pointer border border-gray-300 rounded-lg p-4 hover:bg-gray-50">
                        <input type="checkbox" name="days[]" value="{{ $day }}" {{ in_array($day, old('days', [])) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-3 text-gray-700">{{ $day }}</span>
                    </label>
                @endforeach
            </div>
            @error('days')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Time & Capacity -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Time & Capacity</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Start Time (Optional)</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Leave empty for all-day tours</p>
                    @error('start_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

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

        <!-- Preview -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Preview</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-600 mb-2">This will create dates for:</p>
                <ul class="list-disc pl-5 text-gray-700">
                    <li>Selected days between the date range</li>
                    <li>Time: {{ old('start_time') ? \Carbon\Carbon::parse(old('start_time'))->format('h:i A') : 'All day' }}</li>
                    <li>Slots per date: {{ old('available_slots', $tour->max_people) }}</li>
                    <li>Duplicate dates will be skipped</li>
                </ul>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                Create Dates
            </button>
            <a href="{{ route('agent.tours.dates.index', $tour) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>

    <!-- Instructions -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">How Bulk Dates Work</h3>
        <ul class="space-y-2 text-blue-800">
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2 text-blue-600"></i>
                <span>Dates will be created for each selected day within the range</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2 text-blue-600"></i>
                <span>If a date already exists for that day and time, it will be skipped</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2 text-blue-600"></i>
                <span>Each date will have the same time and capacity settings</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-2 text-blue-600"></i>
                <span>You can always edit individual dates later</span>
            </li>
        </ul>
    </div>
</div>
@endsection
