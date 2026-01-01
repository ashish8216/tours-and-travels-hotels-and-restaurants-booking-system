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
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

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
}
