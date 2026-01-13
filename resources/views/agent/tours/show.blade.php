@extends('agent.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="flex items-center space-x-4 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">{{ $tour->title }}</h1>
                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $tour->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($tour->status) }}
                </span>
            </div>
            <div class="flex items-center text-gray-600 space-x-4">
                <span class="flex items-center">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ $tour->location }}
                </span>
                @if($tour->difficulty_level)
                    <span class="flex items-center">
                        <i class="fas fa-mountain mr-2"></i>
                        {{ $tour->difficulty_level }}
                    </span>
                @endif
                <span class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    {{ $tour->duration }}
                </span>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.tours.edit', $tour) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('agent.tours.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Tours
            </a>
        </div>
    </div>

    <!-- Images -->
    <div class="mb-8">
        @if($tour->image || ($tour->images && count($tour->images) > 0))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Main Image -->
                @if($tour->image)
                    <div class="md:col-span-2">
                        <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}" class="w-full h-64 md:h-96 object-cover rounded-lg">
                    </div>
                @endif

                <!-- Additional Images -->
                @if($tour->images && count($tour->images) > 0)
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($tour->images as $index => $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Tour Image {{ $index + 1 }}" class="w-full h-32 object-cover rounded-lg">
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center">
                <i class="fas fa-images text-4xl text-gray-400"></i>
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Upcoming Dates</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $tour->tourDates->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 text-green-600 rounded-lg mr-4">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $tour->bookings_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 text-amber-600 rounded-lg mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Max Capacity</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $tour->max_people }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg mr-4">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Price</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($tour->price, 2) }}</p>
                    <p class="text-sm text-gray-500">per person</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="md:col-span-2 space-y-8">
            <!-- Description -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Tour Description</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($tour->description)) !!}
                </div>
            </div>

            <!-- Inclusions & Exclusions -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        What's Included
                    </h3>
                    @if($tour->inclusions)
                        <div class="prose max-w-none">
                            {!! nl2br(e($tour->inclusions)) !!}
                        </div>
                    @else
                        <p class="text-gray-500 italic">No inclusions specified</p>
                    @endif
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                        What's Not Included
                    </h3>
                    @if($tour->exclusions)
                        <div class="prose max-w-none">
                            {!! nl2br(e($tour->exclusions)) !!}
                        </div>
                    @else
                        <p class="text-gray-500 italic">No exclusions specified</p>
                    @endif
                </div>
            </div>

            <!-- Requirements -->
            @if($tour->requirements)
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Requirements
                    </h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($tour->requirements)) !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Manage Tour</h3>
                <div class="space-y-3">
                    <a href="{{ route('agent.tours.dates.index', $tour) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-calendar-plus mr-2"></i> Manage Dates
                    </a>
                    <a href="{{ route('agent.tours.dates.bulk-create', $tour) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-calendar-week mr-2"></i> Bulk Add Dates
                    </a>
                    <a href="{{ route('agent.tour-bookings.index') }}?tour_id={{ $tour->id }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-list-alt mr-2"></i> View Bookings
                    </a>
                </div>
            </div>

            <!-- Upcoming Dates -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Upcoming Dates</h3>
                    <a href="{{ route('agent.tours.dates.index', $tour) }}" class="text-sm text-blue-600 hover:underline">
                        View All →
                    </a>
                </div>

                @if($tour->tourDates->count() > 0)
                    <div class="space-y-3">
                        @foreach($tour->tourDates->take(5) as $date)
                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($date->date)->format('D, M d, Y') }}</p>
                                        @if($date->start_time)
                                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($date->start_time)->format('h:i A') }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $date->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($date->status) }}
                                        </span>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $date->booked_slots }} / {{ $date->available_slots }} booked
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No upcoming dates scheduled</p>
                    <a href="{{ route('agent.tours.dates.create', $tour) }}" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-2 rounded-lg font-medium transition">
                        Add First Date
                    </a>
                @endif
            </div>

            <!-- Recent Bookings -->
            @if($upcomingBookings && $upcomingBookings->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Recent Bookings</h3>
                        <a href="{{ route('agent.tour-bookings.index') }}?tour_id={{ $tour->id }}" class="text-sm text-blue-600 hover:underline">
                            View All →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($upcomingBookings as $booking)
                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $booking->user->name ?? 'Guest' }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $booking->tourDate->date->format('M d') }} •
                                            {{ $booking->total_people }} person(s)
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @switch($booking->status)
                                            @case('pending') bg-amber-100 text-amber-800 @break
                                            @case('confirmed') bg-green-100 text-green-800 @break
                                            @case('cancelled') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <div class="mt-2 text-right">
                                    <p class="text-sm font-medium text-gray-800">
                                        ${{ number_format($booking->total_amount, 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
