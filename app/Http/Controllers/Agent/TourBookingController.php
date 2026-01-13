<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourBooking;
use App\Models\TourDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TourBookingController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        $query = TourBooking::byAgent($agent->id)
            ->with(['tour', 'tourDate']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }

        if ($request->filled('date_from')) {
            $query->whereHas('tourDate', function ($q) use ($request) {
                $q->where('date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('tourDate', function ($q) use ($request) {
                $q->where('date', '<=', $request->date_to);
            });
        }

        $bookings = $query->latest()->paginate(15);

        // Get agent's tours for filter dropdown
        $tours = Tour::byAgent($agent->id)
            ->active()
            ->get(['id', 'title']);

        return view('agent.tour-bookings.index', compact('bookings', 'tours'));
    }

    public function create()
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Get agent's tours with available dates
        $tours = Tour::byAgent($agent->id)
            ->active()
            ->with(['tourDates' => function($query) {
                $query->where('date', '>=', now()->toDateString())
                      ->where('status', 'available')
                      ->orderBy('date');
            }])
            ->get();

        return view('agent.tour-bookings.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return back()->with('error', 'Please complete your agent profile first.');
        }

        $validated = $request->validate([
            'tour_date_id' => 'required|exists:tour_dates,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'total_people' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        // Check tour date availability
        $tourDate = TourDate::findOrFail($validated['tour_date_id']);

        // Verify tour belongs to this agent
        if ($tourDate->tour->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check capacity
        if (($tourDate->booked_slots + $validated['total_people']) > $tourDate->available_slots) {
            return back()->withErrors(['total_people' => 'Not enough available slots.']);
        }

        // Generate booking number
        $bookingNumber = 'TB-' . strtoupper(uniqid());

        // Calculate prices
        $pricePerPerson = $tourDate->tour->price;
        $totalAmount = $pricePerPerson * $validated['total_people'];

        // SIMPLE CREATE - Just like room booking
        $booking = TourBooking::create([
            'tour_id' => $tourDate->tour_id,
            'tour_date_id' => $tourDate->id,
            'user_id' => null, // NULL for manual bookings - just like room bookings
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'number_of_people' => $validated['total_people'],
            'price_per_person' => $pricePerPerson,
            'total_amount' => $totalAmount,
            'special_requests' => $validated['special_requests'],
            'status' => 'confirmed',
            'agent_id' => $agent->id,
            'confirmed_at' => now(),
            'booking_number' => $bookingNumber,
        ]);

        // Update booked slots
        $tourDate->increment('booked_slots', $validated['total_people']);

        return redirect()
            ->route('agent.tour-bookings.show', $booking)
            ->with('success', 'Tour booking created successfully!');
    }

    public function show(TourBooking $booking)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return redirect()->route('agent.dashboard')
                ->with('error', 'Please complete your agent profile first.');
        }

        // Check if booking belongs to logged-in agent
        if ($booking->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $booking->load(['tour', 'tourDate']);

        return view('agent.tour-bookings.show', compact('booking'));
    }

    public function confirm(TourBooking $booking)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return back()->with('error', 'Please complete your agent profile first.');
        }

        // Check if booking belongs to logged-in agent
        if ($booking->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$booking->isPending()) {
            return back()->with('error', 'Only pending bookings can be confirmed.');
        }

        $booking->confirm();

        return back()->with('success', 'Booking confirmed successfully!');
    }

    public function cancel(Request $request, TourBooking $booking)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return back()->with('error', 'Please complete your agent profile first.');
        }

        // Check if booking belongs to logged-in agent
        if ($booking->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->isCancelled()) {
            return back()->with('error', 'Booking is already cancelled.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $booking->cancel('agent', $validated['cancellation_reason']);

        return back()->with('success', 'Booking cancelled successfully!');
    }

    public function updateNotes(Request $request, TourBooking $booking)
    {
        $agent = Auth::user()->agent;

        if (!$agent) {
            return back()->with('error', 'Please complete your agent profile first.');
        }

        // Check if booking belongs to logged-in agent
        if ($booking->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'agent_notes' => 'nullable|string|max:1000',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Notes updated successfully!');
    }
}
