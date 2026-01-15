<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        $restaurant = Restaurant::byAgent($agent->id)->first();

        if (!$restaurant) {
            // Auto-create restaurant for restaurant agents
            $restaurant = Restaurant::create([
                'agent_id' => $agent->id,
                'name' => $agent->business_name,
                'location' => $agent->address,
                'phone' => $agent->phone,
                'opening_time' => '09:00',
                'closing_time' => '22:00',
                'capacity' => 50,
                'status' => 'active',
            ]);
        }

        return redirect()->route('agent.restaurants.show', $restaurant);
    }

    public function show(Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $restaurant->loadCount(['tables', 'reservations']);

        $todaysReservations = $restaurant->reservations()
            ->where('reservation_date', now()->toDateString())
            ->orderBy('reservation_time')
            ->limit(5)
            ->get();

        return view('agent.restaurants.show', compact('restaurant', 'todaysReservations'));
    }

    public function edit(Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        return view('agent.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cuisine_type' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            if ($restaurant->image) {
                Storage::disk('public')->delete($restaurant->image);
            }
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        $restaurant->update($validated);

        return redirect()->route('agent.restaurants.show', $restaurant)
            ->with('success', 'Restaurant updated successfully!');
    }
}
