<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tours & Travels') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN (for development - use compiled CSS in production) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#" class="text-2xl font-bold text-gray-900">
                        LOGO
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-8">
                    <a href="/" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium transition {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">
                        Home
                    </a>
                    <a href="#" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium transition {{ request()->routeIs('tours*') ? 'text-blue-600' : '' }}">
                        Tours
                    </a>
                    <a href="#" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium transition {{ request()->routeIs('hotels*') ? 'text-blue-600' : '' }}">
                        Hotels
                    </a>
                    <a href="#" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium transition {{ request()->routeIs('restaurants*') ? 'text-blue-600' : '' }}">
                        Restaurants
                    </a>
                    <a href="#" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium transition {{ request()->routeIs('about') ? 'text-blue-600' : '' }}">
                        About Us
                    </a>
                    <a href="#" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium transition {{ request()->routeIs('blog*') ? 'text-blue-600' : '' }}">
                        Blog
                    </a>
                    <a href="#"class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium transition {{ request()->routeIs('contact') ? 'text-blue-600' : '' }}">
                        Contact
                    </a>
                </div>

                <!-- Auth Links -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                            Sign Up
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-gray-900 hover:text-blue-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">Home</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">Tours</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">Hotels</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">Restaurants</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">About Us</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">Blog</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">Contact</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50 hover:text-blue-600 rounded-md">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 relative" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 relative" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    @endif

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    @stack('scripts')

    <!-- Alpine.js cloaking -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>

