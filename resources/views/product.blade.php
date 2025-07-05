@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="container-md py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">
            <i class="bi bi-bag-fill me-2"></i> Catálogo de Productos
        </h1>
        <p class="lead text-secondary">Encuentra los mejores productos y ofertas</p>
    </div>

    {{-- Búsqueda y Filtros --}}
    <div class="card shadow-sm mb-5 border-0 rounded-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <input type="search" id="buscarInput" class="form-control form-control-lg" placeholder="Buscar por nombre de producto...">
                </div>
                <div class="col-md-3">
                    <select id="filtroOferta" class="form-select form-select-lg">
                        <option value="todos" selected>Todos los productos</option>
                        <option value="oferta">Solo en oferta</option>
                        <option value="sinOferta">Sin oferta</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filtroPrecio" class="form-select form-select-lg">
                        <option value="todos" selected>Todos los precios</option>
                        <option value="menor50">Menor a S/ 50</option>
                        <option value="entre50y100">Entre S/ 50 y S/ 100</option>
                        <option value="mayor100">Mayor a S/ 100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Agrupación lógica: mostrar sólo el producto con lote más cercano --}}
    <div class="row" id="productosGrid">
        @php
            $agrupados = [];
            foreach ($productos as $producto) {
                $clave = strtolower($producto->nombre);
                if (!isset($agrupados[$clave])) {
                    $agrupados[$clave] = [];
                }
                $agrupados[$clave][] = $producto;
            }
        @endphp

        @forelse($agrupados as $grupo)
            @php
                usort($grupo, function($a, $b) {
                    return strtotime($a->lote_mas_cercano->fecha_vencimiento ?? '9999-12-31') <=> strtotime($b->lote_mas_cercano->fecha_vencimiento ?? '9999-12-31');
                });
                $producto = $grupo[0];
                $lote = $producto->lote_mas_cercano;
            @endphp

            @if($lote && $lote->cantidad > 0 && $lote->fecha_vencimiento >= now())
                <div class="col-12 col-sm-6 col-lg-3 mb-4 producto-item"
                     data-nombre="{{ strtolower($producto->nombre) }}"
                     data-oferta="{{ $producto->oferta }}"
                     data-precio="{{ $producto->precio }}">
                    <div class="card h-100 shadow rounded-4 border-0 position-relative">

                        @if ($producto->oferta)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-6">
                                <i class="bi bi-tag-fill me-1"></i> Oferta
                            </span>
                        @endif

                        <img src="{{ asset($producto->imagen) }}"
                             class="card-img-top mx-auto p-4"
                             alt="{{ $producto->nombre }}"
                             style="height: 250px; object-fit: contain; max-width: 90%;">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-semibold fs-5 text-center">{{ $producto->nombre }}</h5>
                            <p class="card-text text-success fs-5 mb-2 text-center">
                                <strong>Precio:</strong> S/ {{ number_format($producto->precio, 2) }}
                            </p>
                            <p class="card-text text-muted flex-grow-1 fs-6 text-center">{{ $producto->descripcion }}</p>

                            <form action="{{ route('carrito.agregar') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">
                                <input type="hidden" name="cantidad" value="1">
                                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-cart-plus fs-5"></i> Agregar al Carrito
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center fs-5">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> No hay productos disponibles.
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- Toastr --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@push('scripts')
<script src="{{ asset('js/catalogo.js') }}"></script>

<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}", '¡Éxito!', { "progressBar": true, "closeButton": true });
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}", 'Error', { "progressBar": true, "closeButton": true });
    @endif
</script>
@endpush
@endsection
