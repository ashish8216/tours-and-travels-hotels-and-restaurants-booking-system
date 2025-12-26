<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .sidebar {
            height: 100vh;
            background: #0d6efd;
            color: white;
        }
        .sidebar a {
            color: #e3f2fd;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
        }
        .sidebar a:hover {
            background: #0b5ed7;
            color: #fff;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <h5 class="text-center py-3 border-bottom">User Panel</h5>

            <a href="{{ url('/user/dashboard') }}">🏠 Dashboard</a>
            <a href="#">🔍 Search Rooms</a>
            <a href="#">📅 My Bookings</a>
            <a href="#">💳 Payments</a>
            <a href="#">👤 Profile</a>

            <form method="POST" action="{{ route('logout') }}" class="mt-3 px-3">
                @csrf
                <button class="btn btn-light w-100">Logout</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 content">
            @yield('content')
        </div>

    </div>
</div>

</body>
</html>
