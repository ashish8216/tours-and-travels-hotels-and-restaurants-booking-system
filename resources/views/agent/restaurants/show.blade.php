@extends('agent.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $restaurant->name }}</h1>
            <div class="flex items-center space-x-4 mt-2 text-gray-600">
                <span class="flex items-center">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ $restaurant->location }}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    {{ \Carbon\Carbon::parse($restaurant->opening_time)->format('h:i A') }} -
                    {{ \Carbon\Carbon::parse($restaurant->closing_time)->format('h:i A') }}
                </span>
                @if($restaurant->cuisine_type)
                    <span class="flex items-center">
                        <i class="fas fa-utensils mr-2"></i>
                        {{ $restaurant->cuisine_type }}
                    </span>
                @endif
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.restaurants.edit', $restaurant) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                Edit Restaurant
            </a>
        </div>
    </div>

    <!-- Image -->
    @if($restaurant->image)
        <div class="mb-8">
            <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-64 object-cover rounded-lg">
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                    <i class="fas fa-chair text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Tables</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $restaurant->tables_count }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 text-green-600 rounded-lg mr-4">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Reservations</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $restaurant->reservations_count }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 text-amber-600 rounded-lg mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Capacity</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $restaurant->capacity }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="md:col-span-2 space-y-6">
            <!-- Description -->
            @if($restaurant->description)
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">About</h2>
                    <p class="text-gray-700">{{ $restaurant->description }}</p>
                </div>
            @endif

            <!-- Today's Reservations -->
            @if($todaysReservations->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Today's Reservations</h2>
                        <a href="{{ route('agent.restaurants.reservations.index', $restaurant) }}?date={{ now()->toDateString() }}" class="text-blue-600 hover:underline">
                            View All →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($todaysReservations as $reservation)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $reservation->customer_name }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }} •
                                            {{ $reservation->number_of_people }} people
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full
                                        {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('agent.restaurants.tables.index', $restaurant) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-chair mr-2"></i> Manage Tables
                    </a>
                    <a href="{{ route('agent.restaurants.reservations.create', $restaurant) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-calendar-plus mr-2"></i> New Reservation
                    </a>
                    <a href="{{ route('agent.restaurants.reservations.index', $restaurant) }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-list-alt mr-2"></i> View Reservations
                    </a>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>
                <div class="space-y-3">
                    @if($restaurant->phone)
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-phone mr-3 text-gray-500"></i>
                            <span>{{ $restaurant->phone }}</span>
                        </div>
                    @endif
                    @if($restaurant->email)
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-envelope mr-3 text-gray-500"></i>
                            <span>{{ $restaurant->email }}</span>
                        </div>
                    @endif
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-map-marker-alt mr-3 text-gray-500"></i>
                        <span>{{ $restaurant->location }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
