@php
    $currentRoute = request()->route()->getName();
@endphp

<div>
    <!-- Botón hamburguesa solo en móvil -->
    <button class="btn btn-primary d-lg-none position-fixed top-0 start-0 m-3 zindex-tooltip" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" title="Abrir menú" style="z-index: 1050;">
        <i class="bi bi-list fs-3"></i>
    </button>

    <!-- Offcanvas para móvil -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="offcanvasSidebarLabel">MarcosFarma</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="text-center mb-4">
                <img src="{{ asset('img/administrador.png') }}" alt="Logo" class="rounded-circle mb-2 shadow" width="80">
                <h5 class="fw-bold mb-0">MarcosFarma</h5>
                <small class="text-muted">Panel de administración</small>
            </div>
            <hr>
            <nav class="nav flex-column px-3">
                <a href="{{ route('dashboard.index') }}" class="nav-link d-flex align-items-center mb-2 {{ $currentRoute === 'dashboard.index' ? 'active fw-bold text-primary' : 'text-dark' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>

                <a href="{{ route('product.index') }}" class="nav-link d-flex align-items-center mb-2 {{ $currentRoute === 'product.index' ? 'active fw-bold text-primary' : 'text-dark' }}">
                    <i class="bi bi-box-seam me-2"></i> Productos
                </a>

                <a href="{{ url('dashboard/users.php') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->is('dashboard/users.php') ? 'active fw-bold text-primary' : 'text-dark' }}">
                    <i class="bi bi-people-fill me-2"></i> Usuarios
                </a>

                <a href="{{ url('dashboard/login.php') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->is('dashboard/login.php') ? 'active fw-bold text-primary' : 'text-dark' }}">
                    <i class="bi bi-person-circle me-2"></i> Perfil
                </a>
            </nav>
            <hr>
            <div class="text-center">
                <small class="text-muted">&copy; {{ date('Y') }} MarcosFarma</small>
            </div>
        </div>
    </div>

    <!-- Sidebar fijo solo en desktop -->
    <div class="sidebar bg-white shadow-sm border-end p-3 position-fixed top-56 start-0 vh-100 d-none d-lg-block" style="width: 250px; z-index: 1030; padding-top: 60px;">
        <div class="text-center mb-4">
            <img src="{{ asset('img/administrador.png') }}" alt="Logo" class="rounded-circle mb-2 shadow" width="80">
            <h5 class="fw-bold mb-0">MarcosFarma</h5>
            <small class="text-muted">Panel de administración</small>
        </div>
        <hr>
        <nav class="nav flex-column">
            <a href="{{ route('dashboard.index') }}" class="nav-link d-flex align-items-center mb-2 {{ $currentRoute === 'dashboard.index' ? 'active fw-bold text-primary' : 'text-dark' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <a href="{{ route('product.index') }}" class="nav-link d-flex align-items-center mb-2 {{ $currentRoute === 'product.index' ? 'active fw-bold text-primary' : 'text-dark' }}">
                <i class="bi bi-box-seam me-2"></i> Productos
            </a>

            <a href="{{ url('dashboard/users.php') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->is('dashboard/users.php') ? 'active fw-bold text-primary' : 'text-dark' }}">
                <i class="bi bi-people-fill me-2"></i> Usuarios
            </a>

            <a href="{{ url('dashboard/login.php') }}" class="nav-link d-flex align-items-center mb-2 {{ request()->is('dashboard/login.php') ? 'active fw-bold text-primary' : 'text-dark' }}">
                <i class="bi bi-person-circle me-2"></i> Perfil
            </a>
        </nav>
        <hr>
        <div class="text-center">
            <small class="text-muted">&copy; {{ date('Y') }} MarcosFarma</small>
        </div>
    </div>

    <!-- Contenido principal que ocupará espacio -->
    <div class="main-content" style="margin-left: 250px; transition: margin-left 0.3s ease;">
        @yield('content')
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const offcanvasSidebar = document.getElementById('offcanvasSidebar');
    const sidebarFijo = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (offcanvasSidebar && sidebarFijo) {
        offcanvasSidebar.addEventListener('show.bs.offcanvas', function () {
            // Cuando se abre el menú hamburguesa, bajar opacidad y desactivar eventos en sidebar fijo
            sidebarFijo.style.opacity = '0.3';
            sidebarFijo.style.pointerEvents = 'none';

            // Ocultar contenido principal cuando offcanvas está abierto (en móvil)
            if(window.innerWidth < 992) {
                mainContent.style.display = 'none';
            }
        });

        offcanvasSidebar.addEventListener('hidden.bs.offcanvas', function () {
            // Cuando se cierra el menú hamburguesa, restaurar opacidad y eventos
            sidebarFijo.style.opacity = '1';
            sidebarFijo.style.pointerEvents = 'auto';

            // Mostrar contenido principal cuando offcanvas se cierra (en móvil)
            if(window.innerWidth < 992) {
                mainContent.style.display = 'block';
            }
        });
    }

    // Ajustar margen y visibilidad cuando se redimensiona la ventana o carga la página
    function updateContentMargin() {
        if(window.innerWidth >= 992) {
            // Escritorio siempre con margen para sidebar fijo
            mainContent.style.marginLeft = '250px';
            mainContent.style.display = 'block'; // aseguramos que esté visible en desktop
        } else {
            // Móvil: si offcanvas está abierto, ocultar contenido, si está cerrado, mostrar contenido
            if (offcanvasSidebar.classList.contains('show')) {
                mainContent.style.marginLeft = '250px';
                mainContent.style.display = 'none'; // ocultar contenido cuando menú activo
            } else {
                mainContent.style.marginLeft = '0';
                mainContent.style.display = 'block'; // mostrar contenido cuando menú cerrado
            }
        }
    }

    window.addEventListener('resize', updateContentMargin);
    updateContentMargin();
});
</script>
