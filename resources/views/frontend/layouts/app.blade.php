<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tours & Travels')</title>

    <!-- Bootstrap 5 CDN (for quick testing) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        footer {
            background: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>

    <!-- SIMPLE NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Tours & Travels</a>

            <div class="ms-auto">
                <a href="{{ route('agent.request.form') }}" class="btn btn-light btn-sm">
                    Become an Agent
                </a>
            </div>
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="text-center py-3 mt-5">
        <small>
            © {{ date('Y') }} Tours & Travels | All Rights Reserved
        </small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
