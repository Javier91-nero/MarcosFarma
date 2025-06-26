<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <title>@yield('title', 'MarcosFarma')</title>

    <link rel="icon" href="{{ asset('img/logo_marcosfarma.png') }}" type="image/png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">

    @include('includes.header')

    <main class="flex-grow-1">
        <div class="container py-4">
            @yield('content')
        </div>
    </main>

    @include('includes.foot')

    <!-- Bootstrap Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Alertas de sesión -->
    @include('includes.alertas')

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>