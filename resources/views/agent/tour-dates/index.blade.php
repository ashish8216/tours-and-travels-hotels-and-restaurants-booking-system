@extends('agent.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tour Dates</h1>
            <p class="text-gray-600 mt-1">Manage dates for {{ $tour->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.tours.dates.bulk-create', $tour) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-calendar-week mr-2"></i> Bulk Add
            </a>
            <a href="{{ route('agent.tours.dates.create', $tour) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-calendar-plus mr-2"></i> Add Date
            </a>
        </div>
    </div>

    <!-- Tour Info Card -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($tour->image)
                    <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}" class="w-16 h-16 object-cover rounded-lg mr-4">
                @endif
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $tour->title }}</h3>
                    <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                        <span class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $tour->location }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $tour->duration }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-users mr-1"></i>
                            Max: {{ $tour->max_people }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('agent.tours.show', $tour) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                View Tour →
            </a>
        </div>
    </div>

    <!-- Dates Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($tourDates->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tourDates as $date)
                            <tr class="hover:bg-gray-50 {{ $date->date < now()->toDateString() ? 'bg-gray-50' : '' }}">
                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($date->date)->format('D, M d, Y') }}
                                    </div>
                                    @if($date->date < now()->toDateString())
                                        <span class="text-xs text-gray-500 italic">Past</span>
                                    @elseif($date->date == now()->toDateString())
                                        <span class="text-xs text-blue-600 font-medium">Today</span>
                                    @endif
                                </td>

                                <!-- Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($date->start_time)
                                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($date->start_time)->format('h:i A') }}</span>
                                    @else
                                        <span class="text-gray-500 italic">All day</span>
                                    @endif
                                </td>

                                <!-- Capacity -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-3">
                                            @php
                                                $percentage = $date->available_slots > 0 ?
                                                    ($date->booked_slots / $date->available_slots) * 100 : 0;
                                            @endphp
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-700">
                                            {{ $date->booked_slots }}/{{ $date->available_slots }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $date->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($date->status) }}
                                    </span>
                                </td>

                                <!-- Bookings -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $activeBookings = $date->bookings()->whereIn('status', ['pending', 'confirmed'])->count();
                                    @endphp
                                    @if($activeBookings > 0)
                                        <a href="{{ route('agent.tour-bookings.index') }}?tour_id={{ $tour->id }}&date_from={{ $date->date }}&date_to={{ $date->date }}"
                                           class="text-blue-600 hover:text-blue-900 font-medium">
                                            {{ $activeBookings }} active
                                        </a>
                                    @else
                                        <span class="text-gray-500">No active bookings</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('agent.tours.dates.edit', [$tour, $date]) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($date->bookings()->whereIn('status', ['pending', 'confirmed'])->count() === 0)
                                            <form action="{{ route('agent.tours.dates.destroy', [$tour, $date]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this date?')"
                                                        class="text-red-600 hover:text-red-900 ml-2">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tourDates->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="px-6 py-12 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Tour Dates Scheduled</h3>
                <p class="text-gray-600 mb-6">Start by adding dates for your tour</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('agent.tours.dates.create', $tour) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Add Single Date
                    </a>
                    <a href="{{ route('agent.tours.dates.bulk-create', $tour) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Bulk Add Dates
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Stats Summary -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Dates</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $tourDates->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 text-green-600 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Available Dates</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $tourDates->where('status', 'available')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 text-amber-600 rounded-lg mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $tour->bookings_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
