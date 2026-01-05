<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomBooking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agent = $user->agent;

        // If agent doesn't exist yet (shouldn't happen but safety check)
        if (!$agent) {
            return redirect()->route('agent.setup')
                ->with('error', 'Please complete your agent profile setup.');
        }

        // Get business types (should always be an array)
        $businessTypes = $agent->business_type ?? ['hotel']; // Note: Changed from business_types to business_type
        $agentId = $agent->id;


        // Initialize stats array
        $stats = [];

        // Only calculate hotel stats if agent has hotel service
        if (in_array('hotel', $businessTypes)) {
            $stats = $this->getHotelStats($agentId);
        }

        // Get recent bookings for dashboard
        $recentBookings = RoomBooking::with(['room', 'user'])
            ->where('agent_id', $agentId)
            ->latest()
            ->limit(5)
            ->get();

        return view('agent.dashboard', [
            'agent' => $agent,
            'businessTypes' => $businessTypes,
            'businessName' => $agent->business_name ?? $user->name,
            'stats' => $stats,
            'recentBookings' => $recentBookings
        ]);
    }

    /**
     * Get hotel-related statistics
     */
    private function getHotelStats($agentId)
    {
        $today = Carbon::today();

        return [
            // Room statistics
            'total_rooms' => Room::where('agent_id', $agentId)->count(),

            // Booking statistics
            'total_bookings' => RoomBooking::where('agent_id', $agentId)->count(),
            'pending_bookings' => RoomBooking::where('agent_id', $agentId)
                ->where('status', 'pending')->count(),
            'confirmed_bookings' => RoomBooking::where('agent_id', $agentId)
                ->where('status', 'confirmed')->count(),
            'checked_in_bookings' => RoomBooking::where('agent_id', $agentId)
                ->where('status', 'checked_in')->count(),
            'today_checkins' => RoomBooking::where('agent_id', $agentId)
                ->whereDate('check_in', $today)->count(),
            'today_checkouts' => RoomBooking::where('agent_id', $agentId)
                ->whereDate('check_out', $today)->count(),
            'active_bookings' => RoomBooking::where('agent_id', $agentId)
                ->whereIn('status', ['confirmed', 'checked_in'])->count(),

            // Revenue statistics
            'total_revenue' => RoomBooking::where('agent_id', $agentId)
                ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                ->sum('total_amount'),
            'today_revenue' => RoomBooking::where('agent_id', $agentId)
                ->whereDate('created_at', $today)
                ->sum('total_amount'),
            'monthly_revenue' => RoomBooking::where('agent_id', $agentId)
                ->whereMonth('created_at', $today->month)
                ->whereYear('created_at', $today->year)
                ->sum('total_amount'),

            // Occupancy rate (simplified calculation)
            'occupancy_rate' => $this->calculateOccupancyRate($agentId),
        ];
    }

    /**
     * Calculate occupancy rate percentage
     */
    private function calculateOccupancyRate($agentId)
    {
        $totalRooms = Room::where('agent_id', $agentId)->count();

        if ($totalRooms == 0) {
            return 0;
        }

        // Count rooms that are booked today
        $today = Carbon::today();
        $bookedRooms = RoomBooking::where('agent_id', $agentId)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->count();

        return round(($bookedRooms / $totalRooms) * 100, 1);
    }
}
