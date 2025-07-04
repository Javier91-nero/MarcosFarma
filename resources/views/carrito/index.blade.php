@extends('layouts.app')

@section('title', 'Mi Carrito - MarcosFarma')

@section('content')
<div class="container py-5">
    <h2 class="text-center fw-bold mb-4 text-primary">
        <i class="bi bi-cart-check me-2"></i>Mi Carrito
    </h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($carrito))
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle me-2"></i>No hay productos en el carrito.
        </div>
        <div class="text-center">
            <a href="{{ url('/catalog') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left-circle me-1"></i> Ir al catálogo
            </a>
        </div>
    @else
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($carrito as $item)
                        @php
                            $subtotal = $item['precio'] * $item['cantidad'];
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td><img src="{{ asset($item['imagen']) }}" width="80" alt="{{ $item['nombre'] }}"></td>
                            <td>{{ $item['nombre'] }}</td>
                            <td>S/ {{ number_format($item['precio'], 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.actualizar') }}" method="POST" class="d-flex justify-content-center align-items-center">
                                    @csrf
                                    <input type="hidden" name="id_producto" value="{{ $item['id'] }}">
                                    <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                            </td>
                            <td>S/ {{ number_format($subtotal, 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.eliminar') }}" method="POST" onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    <input type="hidden" name="id_producto" value="{{ $item['id'] }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="table-light fw-bold">
                        <td colspan="4" class="text-end">Total:</td>
                        <td colspan="2">S/ {{ number_format($total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-end">
            <a href="{{ url('/catalog') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left-circle me-1"></i> Seguir Comprando
            </a>
            <a href="#" class="btn btn-success">
                <i class="bi bi-credit-card-2-front me-1"></i> Proceder al Pago
            </a>
        </div>
    @endif
</div>
@endsection