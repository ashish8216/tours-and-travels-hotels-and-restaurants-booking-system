<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index()
    {
        // Get the agent record for the authenticated user
        $agent = Auth::user()->agent;

        // Check if user has an agent profile
        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        $tours = Tour::byAgent($agent->id)
            ->withCount('tourDates', 'bookings')
            ->latest()
            ->paginate(10);

        return view('agent.tours.index', compact('tours'));
    }

    public function create()
    {
        // Check if user has an agent profile
        if (!Auth::user()->agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        return view('agent.tours.create');
    }

    public function store(Request $request)
    {
        // Get the agent record for the authenticated user
        $agent = Auth::user()->agent;

        // Check if user has an agent profile
        if (!$agent) {
            return back()->withErrors(['error' => 'Please complete your agent profile first.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_people' => 'required|integer|min:1',
            'duration' => 'required|string',
            'duration_hours' => 'nullable|integer|min:1',
            'difficulty_level' => 'nullable|string',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'requirements' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Use the agent ID from the agents table (id = 4 for user_id = 8)
        $validated['agent_id'] = $agent->id;

        // Handle main image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('tours', 'public');
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('tours', 'public');
            }
            $validated['images'] = $images;
        }

        $tour = Tour::create($validated);

        return redirect()
            ->route('agent.tours.show', $tour)
            ->with('success', 'Tour created successfully! Now add available dates.');
    }

    public function show(Tour $tour)
    {
        // Get the agent record for the authenticated user
        $agent = Auth::user()->agent;

        // Check if user has an agent profile
        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $tour->load(['tourDates' => function ($query) {
            $query->where('date', '>=', now()->toDateString())
                ->orderBy('date');
        }]);

        $upcomingBookings = $tour->bookings()
            ->with(['user', 'tourDate'])
            ->whereHas('tourDate', function ($query) {
                $query->where('date', '>=', now()->toDateString());
            })
            ->latest()
            ->limit(10)
            ->get();

        return view('agent.tours.show', compact('tour', 'upcomingBookings'));
    }

    public function edit(Tour $tour)
    {
        // Get the agent record for the authenticated user
        $agent = Auth::user()->agent;

        // Check if user has an agent profile
        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('agent.tours.edit', compact('tour'));
    }

    public function update(Request $request, Tour $tour)
    {
        // Get the agent record for the authenticated user
        $agent = Auth::user()->agent;

        // Check if user has an agent profile
        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_people' => 'required|integer|min:1',
            'duration' => 'required|string',
            'duration_hours' => 'nullable|integer|min:1',
            'difficulty_level' => 'nullable|string',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'requirements' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle main image
        if ($request->hasFile('image')) {
            if ($tour->image) {
                Storage::disk('public')->delete($tour->image);
            }
            $validated['image'] = $request->file('image')->store('tours', 'public');
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            if ($tour->images) {
                foreach ($tour->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('tours', 'public');
            }
            $validated['images'] = $images;
        }

        $tour->update($validated);

        return redirect()
            ->route('agent.tours.show', $tour)
            ->with('success', 'Tour updated successfully!');
    }

    public function destroy(Tour $tour)
    {
        // Get the agent record for the authenticated user
        $agent = Auth::user()->agent;

        // Check if user has an agent profile
        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check for future bookings
        $hasFutureBookings = $tour->bookings()
            ->whereHas('tourDate', function ($query) {
                $query->where('date', '>=', now()->toDateString());
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($hasFutureBookings) {
            return redirect()
                ->route('agent.tours.index')
                ->with('error', 'Cannot delete tour with active bookings.');
        }

        // Delete images
        if ($tour->image) {
            Storage::disk('public')->delete($tour->image);
        }
        if ($tour->images) {
            foreach ($tour->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $tour->delete();

        return redirect()
            ->route('agent.tours.index')
            ->with('success', 'Tour deleted successfully!');
    }
}
