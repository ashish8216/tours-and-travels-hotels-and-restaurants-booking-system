@extends('layouts.app')

@section('title', 'Booking')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 mt-10 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Booking Form</h2>

    <form>
        <label class="block mb-2">Full Name</label>
        <input type="text" class="w-full border p-2 mb-4">

        <label class="block mb-2">Select Service</label>
        <select class="w-full border p-2 mb-4">
            <option>Hotel</option>
            <option>Restaurant</option>
            <option>Tour</option>
        </select>

        <label class="block mb-2">Date</label>
        <input type="date" class="w-full border p-2 mb-4">

        <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">
            Confirm Booking
        </button>
    </form>
</div>
@endsection
