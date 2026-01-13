@extends('frontend.layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gray-50 py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Hero Image -->
            <div class="order-2 md:order-1">
                <div class="bg-gray-300 rounded-lg aspect-video flex items-center justify-center">
                    <svg class="w-32 h-32 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- Hero Content -->
            <div class="order-1 md:order-2">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Explore Amazing Places
                </h1>
                <p class="text-lg text-gray-600 mb-8">
                    Discover the best tours, hotels, and restaurants around the world
                </p>

                <!-- Search Box -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <form action="#" method="GET">
                        <!-- Destination Input -->
                        <div class="mb-4">
                            <input
                                type="text"
                                name="destination"
                                placeholder="Where do you want to go?"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Category Selects -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <select name="tour_type" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Tours</option>
                                <option value="adventure">Adventure Tours</option>
                                <option value="cultural">Cultural Tours</option>
                                <option value="city">City Tours</option>
                                <option value="nature">Nature Tours</option>
                            </select>

                            <select name="hotel_type" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Hotels</option>
                                <option value="luxury">Luxury Hotels</option>
                                <option value="budget">Budget Hotels</option>
                                <option value="resort">Resorts</option>
                                <option value="boutique">Boutique Hotels</option>
                            </select>

                            <select name="restaurant_type" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Restaurants</option>
                                <option value="fine-dining">Fine Dining</option>
                                <option value="casual">Casual Dining</option>
                                <option value="local">Local Cuisine</option>
                                <option value="international">International</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full mt-4 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Destinations Section -->
<section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Featured Destinations</h2>

        <div class="grid md:grid-cols-3 gap-8">
            @forelse($featured_destinations ?? [] as $destination)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="bg-gray-300 aspect-video flex items-center justify-center">
                        @if($destination->image)
                            <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $destination->name ?? 'Featured Destinations' }}</h3>
                        <p class="text-gray-600 mb-4">{{ $destination->description ?? 'Explore amazing destinations' }}</p>
                        <a href="{{ route('destinations.show', $destination->id ?? '#') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Learn More →
                        </a>
                    </div>
                </div>
            @empty
                <!-- Placeholder Cards -->
                @for($i = 0; $i < 3; $i++)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-gray-300 aspect-video flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                {{ $i == 0 ? 'Featured Destinations' : ($i == 1 ? 'Featured Packages' : 'Featured Hotels') }}
                            </h3>
                            <p class="text-gray-600 mb-4">Discover amazing places and experiences</p>
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Learn More →</a>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>
    </div>
</section>

<!-- Special Offer Section -->
<section class="bg-gray-50 py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Offer Image -->
                <div class="bg-gray-300 rounded-lg aspect-video flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <!-- Offer Content -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Special Offer</h2>
                    <p class="text-lg text-gray-600 mb-2">Save up to 20% on select packages.</p>
                    <p class="text-lg text-gray-600 mb-6">Book now and save!</p>
                    <a href="#" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-8 py-3 rounded-lg transition duration-200">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Content -->
<footer class="bg-gray-900 text-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- About Us -->
            <div>
                <h3 class="text-xl font-bold mb-4">About Us</h3>
                <div class="space-y-2">
                    <div class="bg-gray-700 h-4 w-32 rounded"></div>
                    <div class="bg-gray-700 h-4 w-40 rounded"></div>
                    <div class="bg-gray-700 h-4 w-28 rounded"></div>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="hover:text-blue-400 transition">Home</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Tours</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Hotels</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Restaurants</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-xl font-bold mb-4">Contact</h3>
                <div class="space-y-2">
                    <a href="/become-agent" class="hover:text-blue-400 transition">Partner with us</a>
                    <div class="bg-gray-700 h-4 w-32 rounded"></div>
                    <div class="bg-gray-700 h-4 w-40 rounded"></div>
                </div>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-xl font-bold mb-4">Subscribe to Newsletter</h3>
                <form action="#" method="POST">
                    @csrf
                    <input
                        type="email"
                        name="email"
                        placeholder="Email address"
                        required
                        class="w-full px-4 py-2 mb-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-500"
                    >
                    <button type="submit" class="w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} Tours & Travels. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection
