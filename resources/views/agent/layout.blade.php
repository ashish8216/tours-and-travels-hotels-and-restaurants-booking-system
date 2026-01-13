<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agent Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

@php
    $agent = Auth::user()->agent;
    $services = $agent?->business_type ?? []; // ['hotel','restaurant','tour']
@endphp

<body class="bg-gray-50">

    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 md:hidden">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed md:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 md:translate-x-0 transition-transform duration-300">

            <div class="h-full flex flex-col">
                <!-- Logo -->
                <div class="h-16 flex items-center px-6 border-b border-gray-200">
                    <span class="text-lg font-semibold text-gray-800">Agent Panel</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 space-y-1">
                    <a href="{{ route('agent.dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('agent.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-home w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Hotel Service Section -->
                    @if (in_array('hotel', $services))
                        <div x-data="{ hotelOpen: {{ request()->routeIs('agent.rooms.*') || request()->routeIs('agent.room-bookings.*') ? 'true' : 'false' }} }">
                            <button @click="hotelOpen = !hotelOpen"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-lg
                                    {{ request()->routeIs('agent.rooms.*') || request()->routeIs('agent.room-bookings.*')
                                        ? 'bg-blue-50 text-blue-600'
                                        : 'text-gray-600 hover:bg-gray-100' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-hotel w-5 mr-3"></i>
                                    <span>Hotel</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="{ 'rotate-180': hotelOpen }"></i>
                            </button>

                            <div x-show="hotelOpen" x-collapse class="ml-6 mt-1 space-y-1">
                                <a href="{{ route('agent.rooms.index') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm
                                        {{ request()->routeIs('agent.rooms.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-door-open w-4 mr-2"></i>
                                    Manage Rooms
                                </a>
                                <a href="{{ route('agent.room-bookings.index') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm
                                        {{ request()->routeIs('agent.room-bookings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-calendar-check w-4 mr-2"></i>
                                    Room Bookings
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Restaurant Service Section -->
                    @if (in_array('restaurant', $services))
                        <div x-data="{ restaurantOpen: {{ request()->routeIs('agent.restaurant.*') ? 'true' : 'false' }} }">
                            <button @click="restaurantOpen = !restaurantOpen"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-lg
                                    {{ request()->routeIs('agent.restaurant.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-utensils w-5 mr-3"></i>
                                    <span>Restaurant</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="{ 'rotate-180': restaurantOpen }"></i>
                            </button>

                            <div x-show="restaurantOpen" x-collapse class="ml-6 mt-1 space-y-1">
                                <!-- Add restaurant routes when available -->
                                <a href="#"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm text-gray-400 cursor-not-allowed">
                                    <i class="fas fa-chair w-4 mr-2"></i>
                                    Coming Soon
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Tour Service Section -->
                    @if (in_array('tour_guide', $services))
                        <div x-data="{ tourOpen: {{ request()->routeIs('agent.tours.*') || request()->routeIs('agent.tour-bookings.*') ? 'true' : 'false' }} }">
                            <button @click="tourOpen = !tourOpen"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-lg
                                    {{ request()->routeIs('agent.tours.*') || request()->routeIs('agent.tour-bookings.*')
                                        ? 'bg-blue-50 text-blue-600'
                                        : 'text-gray-600 hover:bg-gray-100' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-plane w-5 mr-3"></i>
                                    <span>Tours</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="{ 'rotate-180': tourOpen }"></i>
                            </button>

                            <div x-show="tourOpen" x-collapse class="ml-6 mt-1 space-y-1">
                                <a href="{{ route('agent.tours.index') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm
                                        {{ request()->routeIs('agent.tours.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-map-marked-alt w-4 mr-2"></i>
                                    Manage Tours
                                </a>

                                @if (request()->routeIs('agent.tours.dates.*'))
                                    <!-- Only show this link when viewing specific tour dates -->
                                    @php
                                        $tourId = request()->route('tour');
                                    @endphp
                                    <a href="{{ route('agent.tours.dates.index', ['tour' => $tourId]) }}"
                                        class="flex items-center px-4 py-2 rounded-lg text-sm bg-blue-100 text-blue-700">
                                        <i class="fas fa-calendar-day w-4 mr-2"></i>
                                        Tour Dates
                                    </a>
                                @endif

                                <a href="{{ route('agent.tour-bookings.index') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm
                                        {{ request()->routeIs('agent.tour-bookings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-ticket-alt w-4 mr-2"></i>
                                    Tour Bookings
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- <!-- Common Settings -->
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-user w-5 mr-3"></i>
                            <span>Profile</span>
                        </a>
                    </div> --}}

                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200">
                <div class="px-4 md:px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <button @click="sidebarOpen = true" class="text-gray-500 md:hidden mr-4">
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                            <div class="hidden md:block">
                                @if (request()->routeIs('agent.dashboard'))
                                    <h1 class="text-lg font-medium text-gray-800">Dashboard</h1>
                                @elseif(request()->routeIs('agent.rooms.*'))
                                    <h1 class="text-lg font-medium text-gray-800">Hotel Management</h1>
                                @elseif(request()->routeIs('agent.room-bookings.*'))
                                    <h1 class="text-lg font-medium text-gray-800">Room Bookings</h1>
                                @elseif(request()->routeIs('agent.tours.*'))
                                    <h1 class="text-lg font-medium text-gray-800">Tour Management</h1>
                                @elseif(request()->routeIs('agent.tour-bookings.*'))
                                    <h1 class="text-lg font-medium text-gray-800">Tour Bookings</h1>
                                @else
                                    <h1 class="text-lg font-medium text-gray-800">Agent Panel</h1>
                                @endif
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            {{-- <!-- Notifications (optional) -->
                            <div x-data="{ notificationsOpen: false }" class="relative">
                                <button @click="notificationsOpen = !notificationsOpen"
                                    class="relative text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span class="absolute -top-1 -right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                                </button>
                                <div x-show="notificationsOpen" @click.away="notificationsOpen = false"
                                    class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border py-2">
                                    <div class="px-4 py-2 text-sm text-gray-500 border-b">
                                        Notifications
                                    </div>
                                    <div class="px-4 py-3 text-sm text-gray-600">
                                        No new notifications
                                    </div>
                                </div>
                            </div> --}}

                            <!-- User Dropdown -->
                            <div x-data="{ dropdownOpen: false }" class="relative">
                                <button @click="dropdownOpen = !dropdownOpen"
                                    class="flex items-center space-x-2 focus:outline-none">
                                    <img class="h-8 w-8 rounded-full border"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff"
                                        alt="{{ Auth::user()->name }}">
                                    <span class="hidden md:block text-gray-700">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                                </button>

                                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-1">
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Breadcrumbs (optional) -->
            @if (!request()->routeIs('agent.dashboard'))
                <div class="bg-gray-50 border-b border-gray-200 px-4 md:px-6 py-2">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('agent.dashboard') }}"
                                    class="inline-flex items-center text-sm text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-home mr-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            @if (request()->routeIs('agent.rooms.*'))
                                <li class="inline-flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                                    <span class="text-sm text-gray-500">Hotel</span>
                                </li>
                            @endif
                            @if (request()->routeIs('agent.tours.*'))
                                <li class="inline-flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                                    <span class="text-sm text-gray-500">Tours</span>
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Page Title -->
                    @if (request()->routeIs('agent.tours.dates.*'))
                        @php
                            $tour = request()->route('tour');
                        @endphp
                        @if ($tour)
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-gray-800 mb-2">Tour Dates: {{ $tour->title }}</h1>
                                <p class="text-gray-600">Manage available dates for {{ $tour->title }}</p>
                            </div>
                        @endif
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
