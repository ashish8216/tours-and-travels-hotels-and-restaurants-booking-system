<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agent Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
             class="fixed inset-0 z-20 bg-black/50 md:hidden">
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
                    <a href="{{ url('/agent/dashboard') }}"
                       class="flex items-center px-4 py-3 rounded-lg {{ request()->is('agent/dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-home w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('agent.rooms.index') }}"
                       class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('agent.rooms.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-hotel w-5 mr-3"></i>
                        <span>Manage Rooms</span>
                    </a>

                    <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-calendar-check w-5 mr-3"></i>
                        <span>Bookings</span>
                    </a>

                   
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
                                <h1 class="text-lg font-medium text-gray-800">Dashboard</h1>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <div x-data="{ dropdownOpen: false }" class="relative">
                                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 focus:outline-none">
                                    <img class="h-8 w-8 rounded-full border"
                                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff"
                                         alt="{{ Auth::user()->name }}">
                                    <span class="hidden md:block text-gray-700">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                                </button>

                                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-1">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-6">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
