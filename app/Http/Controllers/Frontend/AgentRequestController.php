<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AgentRequest;
use Illuminate\Http\Request;

class AgentRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required',
            'owner_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'business_types' => 'required|array|min:1',
            'business_types.*' => 'in:hotel,restaurant,both,tour_guide',
        ]);

        AgentRequest::create([
            'business_name'  => $request->business_name,
            'owner_name'     => $request->owner_name,
            'email'          => $request->email,
            'phone'          => $request->phone,

            // ✅ STORE ARRAY DIRECTLY
            'business_type'  => $request->business_types,

            'address'        => $request->address,
            'message'        => $request->message,
            'status'         => 'pending',
        ]);

        return redirect()->back()->with(
            'success',
            'Your request has been submitted. We will contact you soon.'
        );
    }
}
