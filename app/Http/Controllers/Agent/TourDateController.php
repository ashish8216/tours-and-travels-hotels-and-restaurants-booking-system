<?php

// app/Http/Controllers/Agent/TourDateController.php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TourDateController extends Controller
{
    public function index(Tour $tour)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $tourDates = $tour->tourDates()
            ->orderBy('date')
            ->paginate(20);

        return view('agent.tour-dates.index', compact('tour', 'tourDates'));
    }

    public function create(Tour $tour)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('agent.tour-dates.create', compact('tour'));
    }

    public function store(Request $request, Tour $tour)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return back()->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'available_slots' => 'required|integer|min:1|max:' . $tour->max_people,
            'notes' => 'nullable|string',
        ]);

        // Check for duplicate
        $exists = $tour->tourDates()
            ->where('date', $validated['date'])
            ->where('start_time', $validated['start_time'] ?? null)
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'This date and time already exists for this tour.']);
        }

        $validated['tour_id'] = $tour->id;
        $validated['booked_slots'] = 0;
        $validated['status'] = 'available';

        TourDate::create($validated);

        return redirect()
            ->route('agent.tours.dates.index', $tour)
            ->with('success', 'Tour date added successfully!');
    }

    public function edit(Tour $tour, TourDate $date)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('agent.tour-dates.edit', compact('tour', 'date'));
    }

    public function update(Request $request, Tour $tour, TourDate $date)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return back()->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'available_slots' => 'required|integer|min:' . $date->booked_slots,
            'status' => 'required|in:available,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Check for duplicate (excluding current)
        $exists = $tour->tourDates()
            ->where('id', '!=', $date->id)
            ->where('date', $validated['date'])
            ->where('start_time', $validated['start_time'] ?? null)
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'This date and time already exists for this tour.']);
        }

        $date->update($validated);

        return redirect()
            ->route('agent.tours.dates.index', $tour)
            ->with('success', 'Tour date updated successfully!');
    }

    public function destroy(Tour $tour, TourDate $date)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return back()->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check for active bookings
        $hasActiveBookings = $date->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($hasActiveBookings) {
            return back()->with('error', 'Cannot delete date with active bookings.');
        }

        $date->delete();

        return redirect()
            ->route('agent.tours.dates.index', $tour)
            ->with('success', 'Tour date deleted successfully!');
    }

    // Bulk add dates
    public function bulkCreate(Tour $tour)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('agent.tour-dates.bulk-create', compact('tour'));
    }

    public function bulkStore(Request $request, Tour $tour)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return back()->with('error', 'Please complete your agent profile first.');
        }

        // Check if tour belongs to logged-in agent
        if ($tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'days' => 'required|array|min:1',
            'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'available_slots' => 'required|integer|min:1|max:' . $tour->max_people,
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $selectedDays = $validated['days'];

        $created = 0;
        $skipped = 0;

        while ($startDate <= $endDate) {
            if (in_array($startDate->format('l'), $selectedDays)) {
                // Check if date already exists
                $exists = $tour->tourDates()
                    ->where('date', $startDate->toDateString())
                    ->where('start_time', $validated['start_time'] ?? null)
                    ->exists();

                if (!$exists) {
                    TourDate::create([
                        'tour_id' => $tour->id,
                        'date' => $startDate->toDateString(),
                        'start_time' => $validated['start_time'] ?? null,
                        'available_slots' => $validated['available_slots'],
                        'booked_slots' => 0,
                        'status' => 'available',
                    ]);
                    $created++;
                } else {
                    $skipped++;
                }
            }
            $startDate->addDay();
        }

        return redirect()
            ->route('agent.tours.dates.index', $tour)
            ->with('success', "Added {$created} dates. Skipped {$skipped} duplicates.");
    }
}
