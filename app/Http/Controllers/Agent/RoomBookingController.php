<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomBookingController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent;
        $bookings = RoomBooking::with(['room', 'user'])
        ->where('agent_id', $agent->id)
            ->latest()
            ->paginate(10);

        return view('agent.room-bookings.index', compact('bookings'));
    }

    public function create()
    {
        // Get ALL rooms temporarily
        $rooms = Room::all();
        return view('agent.room-bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $agent = Auth::user()->agent;
        $today = Carbon::today()->format('Y-m-d');

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today'  // Prevent past dates
            ],
            'check_out' => [
                'required',
                'date',
                'after:check_in',
                'after:today'  // Also prevent check-out in past
            ],
        ]);

        // Additional manual validation for past dates
        if ($request->check_in < $today) {
            return back()->withErrors(['check_in' => 'Check-in date cannot be in the past.'])->withInput();
        }

        if ($request->check_out <= $today) {
            return back()->withErrors(['check_out' => 'Check-out date must be after today.'])->withInput();
        }

        $room = Room::findOrFail($request->room_id);

        // Calculate nights
        $nights = Carbon::parse($request->check_in)
            ->diffInDays(Carbon::parse($request->check_out));

        $totalAmount = $room->price_per_night * $nights;

        RoomBooking::create([
            'agent_id' => $agent->id,
            'room_id' => $room->id,
            'guest_name' => $request->guest_name,
            'guest_phone' => $request->guest_phone,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'price_per_night' => $room->price_per_night,
            'total_amount' => $totalAmount,
            'status' => 'confirmed',
            'booking_source' => 'manual',
        ]);

        return redirect()
            ->route('agent.room-bookings.index')
            ->with('success', 'Booking created successfully');
    }

    public function availableRooms(Request $request)
    {
        // TEMPORARY: Return ALL rooms
        $rooms = Room::select('id', 'room_name', 'price_per_night')->get();

        // If still empty, create a test room
        if ($rooms->isEmpty()) {
            $room = Room::create([
                'agent_id' => Auth::id(),
                'room_name' => 'Test Room',
                'price_per_night' => 1000,
                'max_guests' => 2
            ]);

            $rooms = collect([[
                'id' => $room->id,
                'room_name' => $room->room_name,
                'price_per_night' => $room->price_per_night
            ]]);
        }

        return response()->json($rooms);
    }
     /**
     * Show booking details
     */
    public function show(RoomBooking $booking)
    {
        // Check if booking belongs to agent
        if ($booking->agent_id !== Auth::user()->agent->id) {
            abort(403, 'Unauthorized');
        }

        $booking->load(['room', 'user']);
        return view('agent.room-bookings.show', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, RoomBooking $booking)
    {
        // Check if booking belongs to agent
        if ($booking->agent_id !== Auth::user()->agent->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        // Prevent invalid status transitions
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['checked_in', 'cancelled'],
            'checked_in' => ['checked_out'],
            'checked_out' => [], // Final state
            'cancelled' => [], // Final state
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus])) {
            return back()->with('error', "Cannot change status from {$oldStatus} to {$newStatus}");
        }

        // Update status
        $booking->update([
            'status' => $newStatus,
            'status_updated_at' => now(),
            'status_notes' => $request->notes
        ]);

        // Log the status change
        \App\Models\BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'notes' => $request->notes,
            'changed_by' => Auth::id()
        ]);

        // Send notifications based on status
        $this->sendStatusNotification($booking, $oldStatus, $newStatus);

        return back()->with('success', "Booking status updated to " . ucfirst($newStatus));
    }

    /**
     * Send notifications when status changes
     */
    private function sendStatusNotification($booking, $oldStatus, $newStatus)
    {
        $guestName = $booking->guest_name ?: $booking->user?->name;
        $guestPhone = $booking->guest_phone ?: $booking->user?->phone;

        // You can implement email/SMS notifications here
        // For now, we'll just log it

        // \Log::info('Booking status changed', [
        //     'booking_id' => $booking->id,
        //     'guest' => $guestName,
        //     'from' => $oldStatus,
        //     'to' => $newStatus,
        //     'agent' => Auth::user()->name
        // ]);

        // Example: Send SMS when booking is confirmed
        if ($newStatus === 'confirmed' && $guestPhone) {
            // Integrate with SMS service like Twilio
            // $this->sendSMS($guestPhone, "Your booking #{$booking->id} has been confirmed!");
        }

        // Example: Send email when checked in
        if ($newStatus === 'checked_in' && $booking->user?->email) {
            // Send check-in email
        }
    }
}
