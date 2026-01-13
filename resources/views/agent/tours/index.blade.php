@extends('agent.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Tours</h1>
            <p class="text-gray-600 mt-1">Manage your tour packages</p>
        </div>
        <a href="{{ route('agent.tours.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
            + Add New Tour
        </a>
    </div>

    <!-- Tours Grid -->
    @if($tours->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tours as $tour)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <!-- Tour Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($tour->image)
                            <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-2 right-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $tour->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($tour->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Tour Info -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $tour->title }}</h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($tour->description, 100) }}</p>

                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $tour->location }}
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-bold text-blue-600">${{ number_format($tour->price, 2) }}</span>
                            <span class="text-sm text-gray-600">per person</span>
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-600 border-t pt-3">
                            <span>{{ $tour->tour_dates_count }} Dates</span>
                            <span>{{ $tour->bookings_count }} Bookings</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('agent.tours.show', $tour) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg text-sm font-semibold transition">
                                View Details
                            </a>
                            <a href="{{ route('agent.tours.edit', $tour) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 text-center py-2 rounded-lg text-sm font-semibold transition">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $tours->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Tours Yet</h3>
            <p class="text-gray-600 mb-6">Start by creating your first tour package</p>
            <a href="{{ route('agent.tours.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                Create Your First Tour
            </a>
        </div>
    @endif
</div>
@endsection
