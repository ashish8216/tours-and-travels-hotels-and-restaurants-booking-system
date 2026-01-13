@extends('agent.layout')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span
                        class="px-3 py-1 text-sm font-medium rounded-full
                    @switch($booking->status)
                        @case('pending') bg-amber-100 text-amber-800 @break
                        @case('confirmed') bg-green-100 text-green-800 @break
                        @case('cancelled') bg-red-100 text-red-800 @break
                        @case('completed') bg-blue-100 text-blue-800 @break
                        @default bg-gray-100 text-gray-800
                    @endswitch">
                        {{ ucfirst($booking->status) }}
                    </span>
                    <span class="text-gray-600">Booked on {{ $booking->created_at->format('F d, Y') }}</span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('agent.tour-bookings.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                    Back to Bookings
                </a>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Left Column - Booking Details -->
            <div class="md:col-span-2 space-y-6">
                <!-- Tour Information -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tour Information</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-32 h-24 bg-gray-100 rounded-lg overflow-hidden mr-4">
                                @if ($booking->tour->image)
                                    <img src="{{ asset('storage/' . $booking->tour->image) }}"
                                        alt="{{ $booking->tour->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-images text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $booking->tour->title }}</h3>
                                <div class="mt-2 space-y-1">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                                        <span>{{ $booking->tour->location }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-clock mr-2 w-4"></i>
                                        <span>{{ $booking->tour->duration }}</span>
                                    </div>
                                    @if ($booking->tour->difficulty_level)
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-mountain mr-2 w-4"></i>
                                            <span>{{ $booking->tour->difficulty_level }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tour Date -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-900">Tour Date & Time</h4>
                                    <p class="text-gray-600">
                                        {{ \Carbon\Carbon::parse($booking->tourDate->date)->format('l, F d, Y') }}
                                        @if ($booking->tourDate->start_time)
                                            at {{ \Carbon\Carbon::parse($booking->tourDate->start_time)->format('h:i A') }}
                                        @endif
                                    </p>
                                </div>
                                <span
                                    class="px-3 py-1 text-sm font-medium rounded-full {{ $booking->tourDate->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($booking->tourDate->status) }}
                                </span>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <p>Capacity: {{ $booking->tourDate->booked_slots }} /
                                    {{ $booking->tourDate->available_slots }} slots booked</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-gray-900">{{ $booking->customer_name ?? ($booking->user->name ?? 'Guest') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <p class="text-gray-900">
                                {{ $booking->customer_email ?? ($booking->user->email ?? 'Not provided') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <p class="text-gray-900">
                                {{ $booking->customer_phone ?? ($booking->user->phone ?? 'Not provided') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                            <p class="text-gray-900">
                                @if ($booking->user)
                                    {{ ucfirst($booking->user->type ?? 'user') }}
                                @else
                                    Manual Booking
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Booking Details</h2>
                    <div class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Booking ID</label>
                                <p class="text-gray-900">#{{ $booking->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Booking Date</label>
                                <p class="text-gray-900">{{ $booking->created_at->format('F d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Number of People</label>
                                <p class="text-gray-900">{{ $booking->total_people }} person(s)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                                <p class="text-xl font-bold text-gray-900">${{ number_format($booking->total_amount, 2) }}
                                </p>
                            </div>
                        </div>

                        @if ($booking->special_requests)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Special Requests</label>
                                <p class="text-gray-900 whitespace-pre-line">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Agent Notes -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Agent Notes</h2>
                    <form action="{{ route('agent.tour-bookings.update-notes', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea name="agent_notes" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('agent_notes', $booking->agent_notes) }}</textarea>
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                                Save Notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Actions & Timeline -->
            <div class="space-y-6">
                <!-- Booking Actions -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Booking Actions</h3>
                    <div class="space-y-3">
                        @if ($booking->isPending())
                            <form action="{{ route('agent.tour-bookings.confirm', $booking) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition flex items-center justify-center">
                                    <i class="fas fa-check mr-2"></i> Confirm Booking
                                </button>
                            </form>
                        @endif

                        @if (!$booking->isCancelled())
                            <button type="button" onclick="openCancelModal()"
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i> Cancel Booking
                            </button>
                        @endif

                        @if ($booking->isConfirmed())
                            <div class="pt-4 border-t border-gray-200">
                                <h4 class="font-medium text-gray-900 mb-2">Check-in Status</h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Tour Date:</span>
                                    <span
                                        class="font-medium {{ $booking->tourDate->date < now() ? 'text-green-600' : 'text-gray-900' }}">
                                        {{ \Carbon\Carbon::parse($booking->tourDate->date)->format('M d, Y') }}
                                    </span>
                                </div>
                                @if ($booking->tourDate->date < now())
                                    <div class="mt-2 text-sm text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i> Tour date has passed
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Booking Timeline -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Booking Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Booking Created</p>
                                <p class="text-sm text-gray-500">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        @if ($booking->confirmed_at)
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Booking Confirmed</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->confirmed_at)->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($booking->cancelled_at)
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                        <i class="fas fa-times text-red-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Booking Cancelled</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->cancelled_at)->format('M d, Y h:i A') }}</p>
                                    @if ($booking->cancellation_reason)
                                        <p class="text-sm text-gray-500 mt-1">Reason: {{ $booking->cancellation_reason }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($booking->tourDate->date < now() && $booking->isConfirmed())
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-flag-checkered text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Tour Completed</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->tourDate->date)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tour Price (x{{ $booking->total_people }})</span>
                            <span>${{ number_format($booking->tour->price * $booking->total_people, 2) }}</span>
                        </div>
                        @if ($booking->tax_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span>${{ number_format($booking->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        @if ($booking->discount_amount > 0)
                            <div class="flex justify-between text-red-600">
                                <span>Discount</span>
                                <span>-${{ number_format($booking->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between pt-2 border-t border-gray-200">
                            <span class="font-semibold">Total Amount</span>
                            <span class="font-bold text-lg">${{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Booking Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Cancel Booking</h3>
            <form action="{{ route('agent.tour-bookings.cancel', $booking) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason *</label>
                    <textarea name="cancellation_reason" rows="4" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Please provide a reason for cancellation..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCancelModal()"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition">
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
