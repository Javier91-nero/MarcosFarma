@extends('layouts.app')

@section('title', 'Mi Carrito')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary">🛒 Mi Carrito</h2>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if(empty($carrito))
        <div class="alert alert-warning">Tu carrito está vacío.</div>
        <a href="{{ url('/catalog') }}" class="btn btn-success mt-3">Ver productos</a>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th></th>
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
                        <td>{{ $item['nombre'] }}</td>
                        <td>S/ {{ number_format($item['precio'], 2) }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                        <td>S/ {{ number_format($subtotal, 2) }}</td>
                        <td>
                            <a href="{{ route('carrito.eliminar', $item['id']) }}" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Quitar
                            </a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>S/ {{ number_format($total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between">
            <a href="{{ route('carrito.vaciar') }}" class="btn btn-outline-danger">Vaciar carrito</a>
            <form action="{{ route('pedidos.guardar') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Realizar Pedido</button>
            </form>
        </div>
    @endif
</div>
@endsection
