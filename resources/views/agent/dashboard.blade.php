@extends('layouts.app')

@section('title', 'Agent Dashboard')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-3xl font-bold mb-6">Agent Panel</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-4 shadow rounded">Manage Bookings</div>
        <div class="bg-white p-4 shadow rounded">Customers</div>
        <div class="bg-white p-4 shadow rounded">Payments</div>
        <div class="bg-white p-4 shadow rounded">Profile</div>
    </div>
</div>
@endsection
