@extends('agent.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reservation #{{ $reservation->reservation_number }}</h1>
            <div class="flex items-center space-x-4 mt-2">
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
                <span class="text-gray-600">Booked on {{ $reservation->created_at->format('F d, Y') }}</span>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.restaurants.reservations.index', $restaurant) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                Back to Reservations
            </a>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        <!-- Left Column - Reservation Details -->
        <div class="md:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <p class="text-gray-900">{{ $reservation->customer_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <p class="text-gray-900">{{ $reservation->customer_email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <p class="text-gray-900">{{ $reservation->customer_phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Reservation Details -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Reservation Details</h2>
                <div class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reservation Number</label>
                            <p class="text-gray-900">{{ $reservation->reservation_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Booking Date</label>
                            <p class="text-gray-900">{{ $reservation->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reservation Date</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('l, F d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reservation Time</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Number of People</label>
                            <p class="text-gray-900">{{ $reservation->number_of_people }} person(s)</p>
                        </div>
                        @if($reservation->table)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Table</label>
                                <p class="text-gray-900">
                                    {{ $reservation->table->table_name ?? 'Table ' . $reservation->table->table_number }}
                                    ({{ $reservation->table->capacity }} people)
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($reservation->special_requests)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Special Requests</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $reservation->special_requests }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Agent Notes -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Agent Notes</h2>
                <form action="{{ route('agent.restaurants.reservations.update-notes', [$restaurant, $reservation]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea name="agent_notes" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('agent_notes', $reservation->agent_notes) }}</textarea>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                            Save Notes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Actions & Timeline -->
        <div class="space-y-6">
            <!-- Reservation Actions -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Reservation Actions</h3>
                <div class="space-y-3">
                    @if($reservation->status == 'pending')
                        <form action="{{ route('agent.restaurants.reservations.confirm', [$restaurant, $reservation]) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition flex items-center justify-center">
                                <i class="fas fa-check mr-2"></i> Confirm Reservation
                            </button>
                        </form>
                    @endif

                    @if($reservation->status == 'confirmed')
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Check-in Status</h4>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Reservation Time:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(!in_array($reservation->status, ['cancelled', 'completed']))
                        <button type="button" onclick="openCancelModal()" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i> Cancel Reservation
                        </button>
                    @endif
                </div>
            </div>

            <!-- Reservation Timeline -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Reservation Timeline</h3>
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Reservation Created</p>
                            <p class="text-sm text-gray-500">{{ $reservation->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($reservation->confirmed_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Reservation Confirmed</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($reservation->confirmed_at)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($reservation->cancelled_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Reservation Cancelled</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($reservation->cancelled_at)->format('M d, Y h:i A') }}</p>
                                @if($reservation->cancellation_reason)
                                    <p class="text-sm text-gray-500 mt-1">Reason: {{ $reservation->cancellation_reason }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Restaurant Information -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Restaurant Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Restaurant:</span>
                        <span class="font-medium">{{ $restaurant->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span>{{ $restaurant->location }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phone:</span>
                        <span>{{ $restaurant->phone ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Reservation Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Cancel Reservation</h3>
        <form action="{{ route('agent.restaurants.reservations.cancel', [$restaurant, $reservation]) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason *</label>
                <textarea name="cancellation_reason" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Please provide a reason for cancellation..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeCancelModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    Confirm Cancellation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target.id === 'cancelModal') {
        closeCancelModal();
    }
});
</script>
@endsection
