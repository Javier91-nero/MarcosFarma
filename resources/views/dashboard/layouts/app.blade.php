<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard - MarcosFarma</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons (original del usuario) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Simple DataTables CSS (Necesario para el estilo de tablas de SB Admin, incluso si no usas la tabla de ejemplo, sus estilos pueden ser útiles) -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />

    <!-- SB Admin CSS Principal (Asegúrate que 'public/css/styles.css' exista) -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

    <!-- Font Awesome (para los iconos de SB Admin) -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Tus CSS personalizados (asegúrate que existan en public/css/) -->
    <link rel="stylesheet" href="{{ asset('css/header_horizontal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

    @stack('styles') {{-- Para estilos adicionales de vistas hijas --}}

    <style>
        /* Estilos personalizados del usuario para alertas */
        .alert {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid;
        }
        .alert-success { border-color: #198754; }
        .alert-danger { border-color: #dc3545; }
        .alert i { margin-right: 8px; }

        /* Ajustes para el layout de SB Admin y tus estilos existentes */
        body.sb-nav-fixed {
            /* SB Admin ya maneja el padding-top para la barra fija */
            padding-top: 0;
        }

        /* Si el sidebar.css ya maneja el margen del contenido principal, puedes eliminar esto */
        /* .main-content {
            margin-left: 250px;
            padding: 20px;
        } */
    </style>
</head>
<body class="sb-nav-fixed"> <!-- Clase necesaria para el layout de barra de navegación fija de SB Admin -->

    <!-- Barra de navegación superior (topnav) - Incluido desde 'dashboard.includes.horizontal' -->
    @include('dashboard.includes.horizontal')

    <!-- Layout principal de SB Admin: Sidenav y Contenido -->
    <div id="layoutSidenav">
        <!-- Barra lateral (sidenav) - Incluido desde 'dashboard.includes.vertical' -->
        @include('dashboard.includes.vertical')

        <!-- Contenido principal y pie de página de SB Admin -->
        <div id="layoutSidenav_content">
            <main> <!-- SB Admin usa <main> directamente dentro de layoutSidenav_content -->
                <div class="container-fluid px-4"> <!-- Usar container-fluid px-4 para el ancho completo y padding de SB Admin -->
                    {{-- Mensajes de éxito, error y validación (movidos aquí desde tu antiguo dashboard.blade.php) --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <i class="bi bi-x-circle-fill"></i>
                            <strong>Se encontraron los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    {{-- Contenido dinámico de las vistas hijas (ej. index.blade.php) --}}
                    @yield('content')
                </div>
            </main>

            <!-- Pie de página de SB Admin -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SB Admin Core JS (Asegúrate que 'public/js/scripts.js' exista) -->
    <script src="{{ asset('js/scripts.js') }}"></script>

    <!-- Simple DataTables JS (Necesario para el estilo de tablas de SB Admin) -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script> {{-- Este script es del demo, puede ser necesario para inicializar datatables --}}

    @stack('scripts') {{-- Para scripts adicionales de vistas hijas --}}
</body>
</html>