@php
    use Illuminate\Support\Facades\DB;

    $user_name = session('nombre') ?? 'Admin';

    $lowStockProducts = DB::select("
        SELECT p.id_producto, p.nombre, SUM(l.cantidad) as total_stock
        FROM productos p
        JOIN lotes l ON p.id_producto = l.id_producto
        GROUP BY p.id_producto, p.nombre
        HAVING total_stock <= 30
    ");

    $expiringLots = DB::select("
        SELECT p.id_producto, p.nombre, l.nro_lote, l.fecha_vencimiento
        FROM productos p
        JOIN lotes l ON p.id_producto = l.id_producto
        WHERE l.fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ");
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top" style="z-index: 1040;">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard.index') }}">
            <img src="{{ asset('img/logo_marcosfarma.png') }}" alt="Logo" class="rounded-circle me-2 shadow-sm" width="40" height="40">
            <span class="fw-bold text-primary">MarcosFarma</span>
        </a>

        <div class="d-flex align-items-center ms-auto gap-3">
            <span class="fw-semibold text-dark d-none d-md-block">
                <i class="bi bi-person-circle me-1"></i> {{ $user_name }}
            </span>

            {{-- Notificaciones --}}
            <div class="dropdown">
                <button class="btn btn-outline-primary position-relative rounded-circle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notificaciones">
                    <i class="bi bi-bell fs-5"></i>
                    @if (count($lowStockProducts) > 0 || count($expiringLots) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-lg p-2" aria-labelledby="notificationDropdown" style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                    @if (count($lowStockProducts) === 0 && count($expiringLots) === 0)
                        <li>
                            <span class="dropdown-item text-muted text-center">
                                <i class="bi bi-check-circle me-2 text-success"></i> No hay notificaciones pendientes
                            </span>
                        </li>
                    @else
                        @if(count($lowStockProducts) > 0)
                            <li>
                                <h6 class="dropdown-header text-dark border-bottom">
                                    <i class="bi bi-box me-1"></i> Productos con bajo stock
                                </h6>
                            </li>
                            @foreach ($lowStockProducts as $product)
                                <li>
                                    <a href="{{ route('product.details', $product->id_producto) }}" class="dropdown-item d-flex align-items-start gap-2">
                                        <i class="bi bi-exclamation-triangle text-warning fs-5 mt-1"></i>
                                        <div>
                                            <strong>{{ $product->nombre }}</strong><br>
                                            <small>Stock actual: {{ $product->total_stock }}</small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @endif

                        @if(count($expiringLots) > 0)
                            <li class="mt-2">
                                <h6 class="dropdown-header text-dark border-bottom">
                                    <i class="bi bi-hourglass-split me-1"></i> Lotes por vencer
                                </h6>
                            </li>
                            @foreach ($expiringLots as $lote)
                                @php
                                    $fechaVenc = new DateTime($lote->fecha_vencimiento);
                                    $hoy = new DateTime();
                                    $vencido = $fechaVenc < $hoy;
                                @endphp
                                <li>
                                    <a href="{{ route('product.details', $lote->id_producto) }}" class="dropdown-item d-flex align-items-start gap-2">
                                        <i class="bi {{ $vencido ? 'bi-x-octagon-fill text-danger' : 'bi-clock-history text-danger' }} fs-5 mt-1"></i>
                                        <div class="{{ $vencido ? 'text-danger fw-bold' : 'text-danger' }}">
                                            <strong>{{ $lote->nombre }}</strong><br>
                                            <small>Lote: {{ $lote->nro_lote }} - 
                                                {{ $vencido ? '¡VENCIDO!' : 'Vence el ' . $fechaVenc->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @endif
                </ul>
            </div>

            {{-- Botón de cerrar sesión --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger rounded-circle" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </form>
        </div>
    </div>
</nav>
