@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')

<!-- HERO HEADER -->
<section class="relative h-[40vh] flex items-center justify-center">

    <img src="{{ asset('images/a.png') }}"
         class="absolute inset-0 w-full h-full object-cover">

    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10 flex items-center justify-center h-full text-white text-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold mb-2">Contact Us</h1>
            <p class="text-lg">We’d love to hear from you</p>
        </div>
    </div>
</section>



        <!-- CONTACT SECTION -->
<section class="py-20 bg-gray-100">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-start">

        <!-- LEFT: CONTACT INFO -->
        <div>
            <h2 class="text-4xl font-bold mb-6 text-gray-800">
                Get in Touch
            </h2>

            <p class="text-lg text-gray-600 leading-relaxed mb-10">
                Have questions about hotel bookings, restaurant reservations,
                or travel packages?
                Our team is here to help you plan your journey smoothly and
                confidently. Feel free to reach out to us anytime  we usually
                respond within 24 hours.
            </p>

            <div class="space-y-6 text-gray-700 text-lg">
                <div>
                    <p class="font-bold">Address</p>
                    <p class="mt-1">Kathmandu, Nepal</p>
                </div>

                <div>
                    <p class="font-bold">Phone</p>
                    <p class="mt-1">+977 9823790410</p>
                </div>

                <div>
                    <p class="font-bold">Email</p>
                    <p class="mt-1">support@travelbooking.com</p>
                </div>
            </div>
        </div>

        <!-- RIGHT: CONTACT FORM -->
        <div class="bg-white p-10 rounded-2xl shadow-xl">
            <h3 class="text-2xl font-bold mb-8 text-gray-800">
                Send Us a Message
            </h3>

            <form class="space-y-6">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">
                        Name
                    </label>
                    <input type="text"
                           placeholder="Enter your full name"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">
                        Email
                    </label>
                    <input type="email"
                           placeholder="Enter your email address"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">
                        Message
                    </label>
                    <textarea rows="5"
                              placeholder="Write your message here..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold text-lg hover:bg-blue-700 transition">
                    Send Message
                </button>
            </form>
        </div>

    </div>
</section>


@endsection
