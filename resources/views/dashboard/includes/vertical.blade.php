@php
    $currentRoute = request()->route()->getName();
@endphp

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

            <a href="{{ route('dashboard.usuarios') }}" class="nav-link d-flex align-items-center mb-2 {{ $currentRoute === 'dashboard.usuarios' ? 'active fw-bold text-primary' : 'text-dark' }}">
                <i class="bi bi-people-fill me-2"></i> Usuarios
            </a>

            <a href="{{ route('dashboard.perfil') }}" class="nav-link d-flex align-items-center mb-2 {{ $currentRoute === 'dashboard.perfil' ? 'active fw-bold text-primary' : 'text-dark' }}">
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

        <a href="{{ route('dashboard.usuarios') }}" class="nav-link d-flex align-items-center mb-2 {{ $currentRoute === 'dashboard.usuarios' ? 'active fw-bold text-primary' : 'text-dark' }}">
            <i class="bi bi-people-fill me-2"></i> Usuarios
        </a>

        <a href="{{ route('dashboard.perfil') }}" class="nav-link d-flex align-items-center mb-2 {{ $currentRoute === 'dashboard.perfil' ? 'active fw-bold text-primary' : 'text-dark' }}">
            <i class="bi bi-person-circle me-2"></i> Perfil
        </a>
    </nav>
    <hr>
    <div class="text-center">
        <small class="text-muted">&copy; {{ date('Y') }} MarcosFarma</small>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const offcanvasSidebar = document.getElementById('offcanvasSidebar');
    const sidebarFijo = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (offcanvasSidebar && sidebarFijo) {
        offcanvasSidebar.addEventListener('show.bs.offcanvas', function () {
            sidebarFijo.style.opacity = '0.3';
            sidebarFijo.style.pointerEvents = 'none';

            if(window.innerWidth < 992) {
                mainContent.style.display = 'none';
            }
        });

        offcanvasSidebar.addEventListener('hidden.bs.offcanvas', function () {
            sidebarFijo.style.opacity = '1';
            sidebarFijo.style.pointerEvents = 'auto';

            if(window.innerWidth < 992) {
                mainContent.style.display = 'block';
            }
        });
    }

    function updateContentMargin() {
        if(window.innerWidth >= 992) {
            mainContent.style.marginLeft = '250px';
            mainContent.style.display = 'block';
        } else {
            if (offcanvasSidebar.classList.contains('show')) {
                mainContent.style.marginLeft = '250px';
                mainContent.style.display = 'none';
            } else {
                mainContent.style.marginLeft = '0';
                mainContent.style.display = 'block';
            }
        }
    }

    window.addEventListener('resize', updateContentMargin);
    updateContentMargin();
});
</script>
@endpush