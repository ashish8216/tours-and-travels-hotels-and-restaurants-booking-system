<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = auth('agent')->user()->restaurants()->get();
        return view('agent.restaurants.index', compact('restaurants'));
    }

    public function show($id)
    {
        $restaurant = auth('agent')->user()->restaurants()->findOrFail($id);
        return view('agent.restaurants.show', compact('restaurant'));
    }
}
