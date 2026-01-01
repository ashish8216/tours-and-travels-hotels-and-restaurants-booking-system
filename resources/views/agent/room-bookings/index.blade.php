@extends('agent.layout')

@section('content')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Room Bookings</h1>
                <p class=" text-gray-600">All hotel bookings (online & manual)</p>
            </div>
            <a href="{{ route('agent.room-bookings.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> New Booking
            </a>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Guest</th>
                        <th class="px-6 py-3 text-left">Room</th>
                        <th class="px-6 py-3 text-left">Dates</th>
                        <th class="px-6 py-3 text-left">Source</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Amount</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <!-- Guest -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">
                                    {{ $booking->user?->name ?? ($booking->guest_name ?? 'N/A') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $booking->user?->email ?? $booking->guest_phone }}
                                </div>
                            </td>

                            <!-- Room -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $booking->room->room_name ?? '—' }}
                            </td>

                            <!-- Dates -->
                            <td class="px-6 py-4 text-gray-600">
                                {{ $booking->check_in->format('d M') }}
                                →
                                {{ $booking->check_out->format('d M Y') }}
                            </td>

                            <!-- Source -->
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs rounded-full
                            {{ $booking->booking_source === 'frontend' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ ucfirst($booking->booking_source) }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs rounded-full
                            @class([
                                'bg-amber-100 text-amber-700' => $booking->status === 'pending',
                                'bg-green-100 text-green-700' => $booking->status === 'confirmed',
                                'bg-red-100 text-red-700' => $booking->status === 'cancelled',
                                'bg-indigo-100 text-indigo-700' => in_array($booking->status, [
                                    'checked_in',
                                    'checked_out',
                                ]),
                            ])">
                                    {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                                </span>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 font-medium text-gray-800">
                                Rs.{{ number_format($booking->total_amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No bookings found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection
