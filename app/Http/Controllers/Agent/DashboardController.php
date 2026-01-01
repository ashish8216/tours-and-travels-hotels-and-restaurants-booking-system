<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

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
        $businessTypes = $agent->business_types ?? ['hotel']; // Default fallback

        return view('agent.dashboard', [
            'agent' => $agent,
            'businessTypes' => $businessTypes,
            'businessName' => $agent->business_name ?? $user->name,
        ]);
    }
}
