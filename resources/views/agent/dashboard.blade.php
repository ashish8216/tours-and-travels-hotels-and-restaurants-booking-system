@extends('agent.layout')
@php
    $agent = Auth::user()->agent;
    $services = $agent?->business_type ?? [];
    $businessName = $businessName ?? $agent?->business_name ?? Auth::user()->name;
    $stats = $stats ?? [];
    $recentBookings = $recentBookings ?? collect();


@endphp
@section('content')
<!-- Welcome -->
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-800">Welcome, {{ $businessName }}</h1>
    <p class="text-gray-600">Dashboard Overview</p>
</div>

<!-- Stats Cards -->
@if(in_array('hotel', $services))
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Rooms -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                <i class="fas fa-door-open text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Rooms</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_rooms'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Active Bookings -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 text-green-600 rounded-lg mr-4">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active Bookings</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['active_bookings'] ?? 0 }}</p>
                <div class="flex space-x-2 mt-1">
                    <span class="text-xs bg-green-50 text-green-700 px-2 py-1 rounded">
                        {{ $stats['confirmed_bookings'] ?? 0 }} Confirmed
                    </span>
                    <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">
                        {{ $stats['checked_in_bookings'] ?? 0 }} Checked-in
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Activity -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-amber-100 text-amber-600 rounded-lg mr-4">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Today's Activity</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['occupancy_rate'] ?? 0 }}%</p>
                <div class="flex space-x-2 mt-1">
                    <span class="text-xs bg-amber-50 text-amber-700 px-2 py-1 rounded">
                        {{ $stats['today_checkins'] ?? 0 }} Check-ins
                    </span>
                    <span class="text-xs bg-purple-50 text-purple-700 px-2 py-1 rounded">
                        {{ $stats['today_checkouts'] ?? 0 }} Check-outs
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 text-purple-600 rounded-lg mr-4">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800">Rs. {{ number_format($stats['total_revenue'] ?? 0) }}</p>
                <div class="flex space-x-2 mt-1">
                    <span class="text-xs bg-green-50 text-green-700 px-2 py-1 rounded">
                        Today: Rs. {{ number_format($stats['today_revenue'] ?? 0) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Summary -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-gray-50 p-4 rounded-lg">
        <p class="text-sm text-gray-500">Total Bookings</p>
        <p class="text-lg font-semibold">{{ $stats['total_bookings'] ?? 0 }}</p>
    </div>
    <div class="bg-gray-50 p-4 rounded-lg">
        <p class="text-sm text-gray-500">Pending</p>
        <p class="text-lg font-semibold text-amber-600">{{ $stats['pending_bookings'] ?? 0 }}</p>
    </div>
    <div class="bg-gray-50 p-4 rounded-lg">
        <p class="text-sm text-gray-500">Monthly Revenue</p>
        <p class="text-lg font-semibold text-green-600">Rs. {{ number_format($stats['monthly_revenue'] ?? 0) }}</p>
    </div>
    <div class="bg-gray-50 p-4 rounded-lg">
        <p class="text-sm text-gray-500">Cancelled</p>
        @php
            $cancelled = isset($stats['total_bookings']) ?
                $stats['total_bookings'] - ($stats['active_bookings'] ?? 0) - ($stats['pending_bookings'] ?? 0) : 0;
        @endphp
        <p class="text-lg font-semibold text-red-600">{{ $cancelled }}</p>
    </div>
</div>
@endif

<!-- Recent Bookings -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Recent Bookings</h3>
        <a href="{{ route('agent.room-bookings.index') }}" class="text-sm text-blue-600 hover:underline">
            View All →
        </a>
    </div>

    @if($recentBookings->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm">
                    <th class="px-6 py-3 text-left">Guest</th>
                    <th class="px-6 py-3 text-left">Room</th>
                    <th class="px-6 py-3 text-left">Dates</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recentBookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">
                            {{ $booking->user?->name ?? ($booking->guest_name ?? 'N/A') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $booking->user?->email ?? $booking->guest_phone }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $booking->room->room_name ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $booking->check_in->format('d M') }} → {{ $booking->check_out->format('d M') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            @switch($booking->status)
                                @case('pending') bg-amber-100 text-amber-800 @break
                                @case('confirmed') bg-green-100 text-green-800 @break
                                @case('checked_in') bg-blue-100 text-blue-800 @break
                                @case('checked_out') bg-purple-100 text-purple-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        Rs. {{ number_format($booking->total_amount, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="px-6 py-8 text-center text-gray-500">
        <i class="fas fa-calendar-times text-3xl mb-3"></i>
        <p>No bookings yet</p>
        <a href="{{ route('agent.room-bookings.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
            Create your first booking
        </a>
    </div>
    @endif
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('agent.rooms.index') }}"
       class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                <i class="fas fa-hotel text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold">Manage Rooms</h4>
                <p class="text-sm text-gray-600">Add, edit or remove rooms</p>
            </div>
        </div>
    </a>

    <a href="{{ route('agent.room-bookings.create') }}"
       class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 text-green-600 rounded-lg mr-4">
                <i class="fas fa-calendar-plus text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold">New Booking</h4>
                <p class="text-sm text-gray-600">Create walk-in/phone booking</p>
            </div>
        </div>
    </a>

    <a href="{{ route('agent.room-bookings.index') }}"
       class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 text-purple-600 rounded-lg mr-4">
                <i class="fas fa-list-alt text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold">View All Bookings</h4>
                <p class="text-sm text-gray-600">Manage all hotel bookings</p>
            </div>
        </div>
    </a>
</div>
@endsection
