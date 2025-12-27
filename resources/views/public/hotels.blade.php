@extends('layouts.app')

@section('title', 'Hotels')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">
    <h2 class="text-3xl font-bold mb-6">Available Hotels</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded shadow">
            <h3 class="font-bold text-lg">Hotel Everest</h3>
            <p class="text-gray-600">Kathmandu</p>
            <p class="text-blue-600 font-semibold mt-2">Rs. 3000 / night</p>
            <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded w-full">
                Book Now
            </button>
        </div>

        <div class="bg-white p-5 rounded shadow">
            <h3 class="font-bold text-lg">Hotel Annapurna</h3>
            <p class="text-gray-600">Pokhara</p>
            <p class="text-blue-600 font-semibold mt-2">Rs. 4500 / night</p>
            <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded w-full">
                Book Now
            </button>
        </div>
    </div>
</div>
@endsection
