<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantBooking;

class BookingController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();

        $bookings = RestaurantBooking::whereHas('restaurant.agents', function ($q) use ($agent) {
            $q->where('agent_id', $agent->id);
        })->orderBy('booking_date', 'desc')->get();

        return view('agent.bookings.index', compact('bookings'));
    }

    public function confirm($id)
    {
        $agent = auth('agent')->user();

        $booking = RestaurantBooking::whereHas('restaurant.agents', function ($q) use ($agent) {
            $q->where('agent_id', $agent->id);
        })->findOrFail($id);

        $booking->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Booking confirmed.');
    }
}
