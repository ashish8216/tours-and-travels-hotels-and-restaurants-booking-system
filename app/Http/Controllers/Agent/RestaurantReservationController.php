<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantReservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RestaurantReservationController extends Controller
{
    public function index(Request $request, Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $query = $restaurant->reservations()->with(['table']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('reservation_date', $request->date);
        }

        $reservations = $query->latest()->paginate(15);

        return view('agent.restaurant-reservations.index', compact('restaurant', 'reservations'));
    }

    public function create(Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $tables = $restaurant->tables()->where('status', 'available')->get();

        return view('agent.restaurant-reservations.create', compact('restaurant', 'tables'));
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $validated = $request->validate([
            'restaurant_table_id' => 'nullable|exists:restaurant_tables,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'number_of_people' => 'required|integer|min:1',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'special_requests' => 'nullable|string',
        ]);

        // Check table availability
        if ($validated['restaurant_table_id']) {
            $table = RestaurantTable::find($validated['restaurant_table_id']);

            if (!$table->isAvailableFor($validated['reservation_date'], $validated['reservation_time'])) {
                return back()->withErrors(['restaurant_table_id' => 'Table not available at selected time.']);
            }
        }

        $reservation = RestaurantReservation::create([
            'restaurant_id' => $restaurant->id,
            'restaurant_table_id' => $validated['restaurant_table_id'],
            'user_id' => null,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'number_of_people' => $validated['number_of_people'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'status' => 'confirmed',
            'special_requests' => $validated['special_requests'],
            'agent_id' => $agent->id,
            'confirmed_at' => now(),
            'reservation_number' => 'RES-' . strtoupper(Str::random(8)),
        ]);

        return redirect()->route('agent.restaurants.reservations.show', [$restaurant, $reservation])
            ->with('success', 'Reservation created successfully!');
    }

    public function show(Restaurant $restaurant, RestaurantReservation $reservation)
    {
        $agent = Auth::user()->agent;

        if ($reservation->restaurant_id !== $restaurant->id || $restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $reservation->load(['table']);

        return view('agent.restaurant-reservations.show', compact('restaurant', 'reservation'));
    }

    public function confirm(Restaurant $restaurant, RestaurantReservation $reservation)
    {
        $agent = Auth::user()->agent;

        if ($reservation->restaurant_id !== $restaurant->id || $restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        if (!$reservation->isPending()) {
            return back()->with('error', 'Only pending reservations can be confirmed.');
        }

        $reservation->confirm();

        return back()->with('success', 'Reservation confirmed successfully!');
    }

    public function cancel(Request $request, Restaurant $restaurant, RestaurantReservation $reservation)
    {
        $agent = Auth::user()->agent;

        if ($reservation->restaurant_id !== $restaurant->id || $restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $reservation->cancel($validated['cancellation_reason']);

        return back()->with('success', 'Reservation cancelled successfully!');
    }

    public function updateNotes(Request $request, Restaurant $restaurant, RestaurantReservation $reservation)
    {
        $agent = Auth::user()->agent;

        if ($reservation->restaurant_id !== $restaurant->id || $restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $validated = $request->validate([
            'agent_notes' => 'nullable|string|max:1000',
        ]);

        $reservation->update($validated);

        return back()->with('success', 'Notes updated successfully!');
    }
}
