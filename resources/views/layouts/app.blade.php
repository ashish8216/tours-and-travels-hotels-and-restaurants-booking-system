<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Online Booking System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    @include('partials.navbar')

<main class="min-h-screen pt-20">
    @yield('content')
</main>


    @include('partials.footer')

</body>
</html>
