@extends('agent.layout')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Booking #{{ $booking->id }}</h1>
            <p class="text-gray-600">Booking details and status management</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.room-bookings.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                ← Back to List
            </a>
            <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-200">
                🖨️ Print
            </button>
        </div>
    </div>

    <!-- Status Alert -->
    <div class="mb-6">
        <div class="flex items-center justify-between p-4 rounded-lg
            @switch($booking->status)
                @case('pending') bg-amber-50 border border-amber-200 @break
                @case('confirmed') bg-green-50 border border-green-200 @break
                @case('checked_in') bg-blue-50 border border-blue-200 @break
                @case('checked_out') bg-purple-50 border border-purple-200 @break
                @case('cancelled') bg-red-50 border border-red-200 @break
            @endswitch">
            <div class="flex items-center">
                <div class="p-2 rounded-lg mr-4
                    @switch($booking->status)
                        @case('pending') bg-amber-100 text-amber-700 @break
                        @case('confirmed') bg-green-100 text-green-700 @break
                        @case('checked_in') bg-blue-100 text-blue-700 @break
                        @case('checked_out') bg-purple-100 text-purple-700 @break
                        @case('cancelled') bg-red-100 text-red-700 @break
                    @endswitch">
                    @switch($booking->status)
                        @case('pending') ⏳ @break
                        @case('confirmed') ✅ @break
                        @case('checked_in') 🏨 @break
                        @case('checked_out') 🚪 @break
                        @case('cancelled') ❌ @break
                    @endswitch
                </div>
                <div>
                    <h3 class="font-semibold">Status: {{ ucfirst($booking->status) }}</h3>
                    <p class="text-sm text-gray-600">
                        Last updated: {{ $booking->status_updated_at ? $booking->status_updated_at->format('d M Y, h:i A') : 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Status Actions Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-50">
                    Change Status
                </button>

                <div x-show="open" @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-10">
                    <form method="POST" action="{{ route('agent.room-bookings.update-status', $booking) }}"
                          class="p-2 space-y-2">
                        @csrf

                        <!-- Status Options -->
                        @if($booking->status == 'pending')
                            <button type="submit" name="status" value="confirmed"
                                    class="w-full text-left px-3 py-2 text-sm text-green-700 hover:bg-green-50 rounded">
                                ✅ Confirm Booking
                            </button>
                            <button type="submit" name="status" value="cancelled"
                                    class="w-full text-left px-3 py-2 text-sm text-red-700 hover:bg-red-50 rounded">
                                ❌ Cancel Booking
                            </button>
                        @elseif($booking->status == 'confirmed')
                            <button type="submit" name="status" value="checked_in"
                                    class="w-full text-left px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded">
                                🏨 Check-in Guest
                            </button>
                            <button type="submit" name="status" value="cancelled"
                                    class="w-full text-left px-3 py-2 text-sm text-red-700 hover:bg-red-50 rounded">
                                ❌ Cancel Booking
                            </button>
                        @elseif($booking->status == 'checked_in')
                            <button type="submit" name="status" value="checked_out"
                                    class="w-full text-left px-3 py-2 text-sm text-purple-700 hover:bg-purple-50 rounded">
                                🚪 Check-out Guest
                            </button>
                        @endif

                        <!-- Notes Input -->
                        <div class="pt-2 border-t">
                            <textarea name="notes" placeholder="Add notes (optional)"
                                      class="w-full text-sm border rounded p-2"
                                      rows="2"></textarea>
                            <button type="submit"
                                    class="w-full mt-2 px-3 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">
                                Update with Notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Booking Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Guest Information -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Guest Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Guest Name</p>
                        <p class="font-medium">{{ $booking->guest_name ?? $booking->user?->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="font-medium">{{ $booking->guest_phone ?? $booking->user?->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $booking->user?->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Booking Source</p>
                        <span class="px-2 py-1 text-xs rounded
                            {{ $booking->booking_source == 'frontend' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ ucfirst($booking->booking_source) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Booking Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Room</p>
                        <p class="font-medium text-lg">{{ $booking->room->room_name }}</p>
                        <p class="text-gray-600">Rs. {{ number_format($booking->room->price_per_night, 2) }}/night</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Check-in</p>
                            <p class="font-medium">{{ $booking->check_in->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Check-out</p>
                            <p class="font-medium">{{ $booking->check_out->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Nights</p>
                        <p class="font-medium">
                            {{ $booking->check_in->diffInDays($booking->check_out) }} nights
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="font-medium text-xl text-green-600">
                            Rs. {{ number_format($booking->total_amount, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Status History</h3>
                <div class="space-y-4">
                    @forelse($booking->statusLogs as $log)
                    <div class="flex items-start space-x-3">
                        <div class="mt-1">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                👤
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="font-medium">{{ $log->changedBy->name }}</p>
                                <p class="text-sm text-gray-500">{{ $log->created_at->format('d M, h:i A') }}</p>
                            </div>
                            <p class="text-sm">
                                Changed status from
                                <span class="font-medium">{{ $log->from_status }}</span> to
                                <span class="font-medium">{{ $log->to_status }}</span>
                            </p>
                            @if($log->notes)
                                <p class="text-sm text-gray-600 mt-1">"{{ $log->notes }}"</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No status changes yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Notes -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="#" class="block p-3 border rounded-lg hover:bg-gray-50">
                        📄 Generate Invoice
                    </a>
                    <a href="#" class="block p-3 border rounded-lg hover:bg-gray-50">
                        ✉️ Send Confirmation Email
                    </a>
                    <a href="#" class="block p-3 border rounded-lg hover:bg-gray-50">
                        📱 Send SMS Reminder
                    </a>
                    <button onclick="document.getElementById('notesModal').classList.remove('hidden')"
                            class="w-full p-3 border rounded-lg hover:bg-gray-50 text-left">
                            📝 Add Private Notes
                    </button>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Booking Timeline</h3>
                <div class="space-y-4">
                    @php
                        $timeline = [
                            ['status' => 'pending', 'label' => 'Booking Created', 'date' => $booking->created_at],
                            ['status' => 'confirmed', 'label' => 'Booking Confirmed', 'date' => $booking->status == 'confirmed' ? $booking->status_updated_at : null],
                            ['status' => 'checked_in', 'label' => 'Guest Checked-in', 'date' => $booking->status == 'checked_in' ? $booking->status_updated_at : null],
                            ['status' => 'checked_out', 'label' => 'Guest Checked-out', 'date' => $booking->status == 'checked_out' ? $booking->status_updated_at : null],
                        ];
                    @endphp

                    @foreach($timeline as $item)
                        <div class="flex items-center">
                            <div class="mr-4">
                                @if($item['date'])
                                    <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                        ✅
                                    </div>
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">
                                        ⏳
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium">{{ $item['label'] }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $item['date'] ? $item['date']->format('d M Y, h:i A') : 'Pending' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="font-semibold text-lg mb-4">Add Private Notes</h3>
        <form method="POST" action="{{ route('agent.room-bookings.update-status', $booking) }}">
            @csrf
            <input type="hidden" name="status" value="{{ $booking->status }}">
            <textarea name="notes" class="w-full border rounded p-3" rows="4"
                      placeholder="Add private notes about this booking..."></textarea>
            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('notesModal').classList.add('hidden')"
                        class="px-4 py-2 border rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Notes</button>
            </div>
        </form>
    </div>
</div>

<!-- Include Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
