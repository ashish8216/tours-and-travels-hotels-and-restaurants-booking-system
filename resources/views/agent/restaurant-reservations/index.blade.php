@extends('agent.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Restaurant Reservations</h1>
            <p class="text-gray-600 mt-1">Manage reservations for {{ $restaurant->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.restaurants.reservations.create', $restaurant) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                + New Reservation
            </a>
            <a href="{{ route('agent.restaurants.show', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Restaurant
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('agent.restaurants.reservations.index', $restaurant) }}" method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-3 md:gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="seated" {{ request('status') == 'seated' ? 'selected' : '' }}>Seated</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    Apply Filters
                </button>
                <a href="{{ route('agent.restaurants.reservations.index', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Reservations Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($reservations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reservations as $reservation)
                            <tr class="hover:bg-gray-50">
                                <!-- Reservation Number -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $reservation->reservation_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->created_at->format('M d, Y') }}</div>
                                </td>

                                <!-- Customer -->
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $reservation->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->customer_email }}</div>
                                    @if($reservation->customer_phone)
                                        <div class="text-sm text-gray-500">{{ $reservation->customer_phone }}</div>
                                    @endif
                                </td>

                                <!-- Date & Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('D, M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}
                                    </div>
                                </td>

                                <!-- Details -->
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <div class="text-gray-900">{{ $reservation->number_of_people }} person(s)</div>
                                        @if($reservation->table)
                                            <div class="text-gray-600">Table: {{ $reservation->table->table_name ?? $reservation->table->table_number }}</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-medium rounded-full
                                        @switch($reservation->status)
                                            @case('pending') bg-amber-100 text-amber-800 @break
                                            @case('confirmed') bg-green-100 text-green-800 @break
                                            @case('seated') bg-blue-100 text-blue-800 @break
                                            @case('completed') bg-purple-100 text-purple-800 @break
                                            @case('cancelled') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('agent.restaurants.reservations.show', [$restaurant, $reservation]) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($reservation->status == 'pending')
                                            <form action="{{ route('agent.restaurants.reservations.confirm', [$restaurant, $reservation]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 ml-2">
                                                    <i class="fas fa-check"></i> Confirm
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
                {{ $reservations->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="px-6 py-12 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reservations Found</h3>
                <p class="text-gray-600 mb-6">No reservations match your current filters.</p>
                <a href="{{ route('agent.restaurants.reservations.create', $restaurant) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Create First Reservation
                </a>
            </div>
        @endif
    </div>

    <!-- Stats Summary -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 text-amber-600 rounded-lg mr-4">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $reservations->where('status', 'pending')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 text-green-600 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Confirmed</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $reservations->where('status', 'confirmed')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                    <i class="fas fa-chair text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Seated</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $reservations->where('status', 'seated')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg mr-4">
                    <i class="fas fa-flag-checkered text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Completed</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $reservations->where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
