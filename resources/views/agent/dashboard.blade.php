@extends('agent.layout')

@section('content')
<!-- Welcome -->
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}</h1>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                <i class="fas fa-door-open text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Rooms</p>
                <p class="text-2xl font-bold text-gray-800">42</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 text-green-600 rounded-lg mr-4">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active Bookings</p>
                <p class="text-2xl font-bold text-gray-800">127</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-amber-100 text-amber-600 rounded-lg mr-4">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Occupancy Rate</p>
                <p class="text-2xl font-bold text-gray-800">78%</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 text-purple-600 rounded-lg mr-4">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Revenue</p>
                <p class="text-2xl font-bold text-gray-800">₹1.2M</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="font-semibold text-gray-800">Recent Bookings</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm">
                    <th class="px-6 py-3 text-left">Guest</th>
                    <th class="px-6 py-3 text-left">Room</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">Niraj Risal</div>
                        <div class="text-sm text-gray-500">Today, 09:30 AM</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Deluxe Room</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Pending</span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">₹3,000</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">Ram Sharma</div>
                        <div class="text-sm text-gray-500">Yesterday, 3:45 PM</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Standard Room</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Confirmed</span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">₹2,500</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
