<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantTableController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $tables = $restaurant->tables()->paginate(20);

        return view('agent.restaurant-tables.index', compact('restaurant', 'tables'));
    }

    public function create(Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        return view('agent.restaurant-tables.create', compact('restaurant'));
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id) {
            abort(403);
        }

        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'table_name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:20',
            'type' => 'required|in:indoor,outdoor,private_room,bar',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $restaurant->tables()->create($validated);

        return redirect()->route('agent.restaurants.tables.index', $restaurant)
            ->with('success', 'Table added successfully!');
    }

    public function edit(Restaurant $restaurant, RestaurantTable $table)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id || $table->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        return view('agent.restaurant-tables.edit', compact('restaurant', 'table'));
    }

    public function update(Request $request, Restaurant $restaurant, RestaurantTable $table)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id || $table->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'table_name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1|max:20',
            'type' => 'required|in:indoor,outdoor,private_room,bar',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $table->update($validated);

        return redirect()->route('agent.restaurants.tables.index', $restaurant)
            ->with('success', 'Table updated successfully!');
    }

    public function destroy(Restaurant $restaurant, RestaurantTable $table)
    {
        $agent = Auth::user()->agent;

        if ($restaurant->agent_id !== $agent->id || $table->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        if ($table->reservations()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return back()->with('error', 'Cannot delete table with active reservations.');
        }

        $table->delete();

        return redirect()->route('agent.restaurants.tables.index', $restaurant)
            ->with('success', 'Table deleted successfully!');
    }
}
