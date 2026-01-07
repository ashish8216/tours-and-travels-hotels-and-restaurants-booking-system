@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <!-- HERO SECTION -->
    <section class="relative h-[85vh]">
        <img src="{{ url('images/a.png') }}" alt="Travel Booking" class="absolute inset-0 w-full h-full object-cover object-center">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40 flex items-center">
            <div class="max-w-6xl mx-auto px-6 text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Book Hotels, Restaurants <br> & Tours Easily
                </h1>
                <p class="mb-6 text-lg">
                    Plan your travel with comfort and confidence
                </p>

                <a href="/booking"
                    class="bg-white text-blue-600 px-6 py-3 rounded font-semibold hover:bg-gray-100 text-align:center">
                    Start Booking
                </a>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section class="-mt-20 relative z-10">
        <div class="max-w-6xl mx-auto px-6">
            <div class="bg-white rounded-xl shadow-lg grid grid-cols-1 md:grid-cols-3 gap-6 p-8">

                <!-- Hotels -->
                <div class="text-center">
                    {{-- <img src="{{ asset('images/a.png') }}"
                     class="mx-auto h-16 mb-4"> --}}
                    <h3 class="text-xl font-bold">Hotels</h3>
                    <p class="text-gray-600 mt-2">
                        Find comfortable hotels at best prices
                    </p>
                </div>

                <!-- Restaurants -->
                <div class="text-center">
                    {{-- <img src="{{ asset('images/a.png') }}"
                     class="mx-auto h-16 mb-4"> --}}
                    <h3 class="text-xl font-bold">Restaurants</h3>
                    <p class="text-gray-600 mt-2">
                        Reserve tables at popular restaurants
                    </p>
                </div>

                <!-- Tours -->
                <div class="text-center">
                    {{-- <img src="{{ asset('images/a.png') }}"
                     class="mx-auto h-16 mb-4"> --}}
                    <h3 class="text-xl font-bold">Tours</h3>
                    <p class="text-gray-600 mt-2">
                        Book exciting tours & travel packages
                    </p>
                </div>

            </div>
        </div>
    </section>

@endsection
