<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-2">
    <div class="container d-flex justify-content-between align-items-center">

        {{-- Logo y Título --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <img src="{{ asset('img/logo_marcosfarma.png') }}" alt="Logo" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
            <span class="fw-bold fs-5">MarcosFarma</span>
        </a>

        {{-- Botón Hamburguesa --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menú de Navegación --}}
        <div class="collapse navbar-collapse justify-content-end mt-3 mt-lg-0" id="mainNavbar">
            <ul class="navbar-nav align-items-center gap-3">

                {{-- Inicio --}}
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link text-white fw-medium d-flex align-items-center">
                        <i class="bi bi-house-door-fill me-2"></i> Inicio
                    </a>
                </li>

                {{-- Productos --}}
                <li class="nav-item">
                    <a href="{{ url('/catalog') }}" class="nav-link text-white fw-medium d-flex align-items-center">
                        <i class="bi bi-box-seam me-2"></i> Productos
                    </a>
                </li>

                {{-- Nosotros --}}
                <li class="nav-item">
                    <a href="{{ url('/nosotros') }}" class="nav-link text-white fw-medium d-flex align-items-center">
                        <i class="bi bi-people-fill me-2"></i> Nosotros
                    </a>
                </li>

                {{-- Usuario Autenticado / Login --}}
                <li class="nav-item dropdown">
                    @if(isset($clienteAuth))
                        {{-- Usuario autenticado --}}
                        <a class="nav-link dropdown-toggle text-white fw-medium d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i> {{ $clienteAuth->nombre }} {{ $clienteAuth->apellido }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ url('/perfil') }}">
                                    Mi perfil
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    @else
                        {{-- Usuario no autenticado --}}
                        <a href="{{ url('/login') }}" class="nav-link text-white fw-medium d-flex align-items-center">
                            <i class="bi bi-person-circle me-2"></i> Iniciar sesión
                        </a>
                    @endif
                </li>

            </ul>
        </div>
    </div>
</nav>