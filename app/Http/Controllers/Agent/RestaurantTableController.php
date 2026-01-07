<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\RestaurantTable;

class RestaurantTableController extends Controller
{
    public function index()
    {
        // Show all tables for all agent restaurants
        $agent = auth('agent')->user();
        $restaurants = $agent->restaurants()->with('tables')->get();

        return view('agent.tables.index', compact('restaurants'));
    }

    public function create($restaurantId)
    {
        $restaurant = auth('agent')->user()->restaurants()->findOrFail($restaurantId);
        return view('agent.tables.create', compact('restaurant'));
    }

    public function store(Request $request, $restaurantId)
    {
        $restaurant = auth('agent')->user()->restaurants()->findOrFail($restaurantId);

        $request->validate([
            'table_number' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $restaurant->tables()->create($request->all());

        return redirect()->route('agent.tables.index')->with('success', 'Table created successfully.');
    }
}
