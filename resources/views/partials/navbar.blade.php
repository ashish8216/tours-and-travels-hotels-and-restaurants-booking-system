<nav class="fixed top-0 w-full bg-gray-900 text-white z-50">
    <div class="max-w-6xl mx-auto px-6 flex justify-between items-center h-16">

        <!-- Left menu -->
        <div class="flex space-x-6">
            <a href="/" class="hover:text-blue-400">Home</a>
            <a href="/hotels" class="hover:text-blue-400">Hotels</a>
            <a href="/restaurants" class="hover:text-blue-400">Restaurants</a>
            <a href="/contact" class="hover:text-blue-400">Contact</a>
        </div>

        <!-- Right menu -->
        <div>
            @guest
                <a href="{{ route('login') }}"
                   class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700">
                    Login
                </a>
            @endguest

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
                        Logout
                    </button>
                </form>
            @endauth
        </div>

    </div>
</nav>
