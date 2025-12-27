@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-3xl font-bold mb-6">Welcome, User</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 shadow rounded">My Bookings</div>
        <div class="bg-white p-4 shadow rounded">Payments</div>
        <div class="bg-white p-4 shadow rounded">Profile</div>
    </div>
</div>
@endsection
