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

            <div class="dropdown">
                <button class="btn btn-outline-primary position-relative rounded-circle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notificaciones">
                    <i class="bi bi-bell fs-5"></i>
                    @if (count($lowStockProducts) > 0 || count($expiringLots) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="min-width: 300px;">
                    @if (count($lowStockProducts) === 0 && count($expiringLots) === 0)
                        <li><span class="dropdown-item text-muted"><i class="bi bi-check-circle me-2"></i>No hay notificaciones</span></li>
                    @endif

                    @foreach ($lowStockProducts as $product)
                        <li>
                            <a href="{{ route('product.details', $product->id_producto) }}" class="dropdown-item text-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $product->nombre }} - Stock: {{ $product->total_stock }}
                            </a>
                        </li>
                    @endforeach

                    @foreach ($expiringLots as $lote)
                        @php
                            $fechaVenc = new DateTime($lote->fecha_vencimiento);
                            $hoy = new DateTime();
                            $vencido = $fechaVenc < $hoy;
                        @endphp
                        <li>
                            <a href="{{ route('product.details', $lote->id_producto) }}" class="dropdown-item {{ $vencido ? 'text-danger' : 'text-warning' }}">
                                <i class="bi {{ $vencido ? 'bi-x-circle' : 'bi-clock-history' }} me-2"></i>
                                {{ $lote->nombre }} - Lote {{ $lote->nro_lote }}:
                                {{ $vencido ? '¡Vencido!' : 'Vence pronto (' . $fechaVenc->format('d/m/Y') . ')' }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger rounded-circle" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </form>
        </div>
    </div>
</nav>