@extends('layouts.app')

@section('title', 'Detalle del Pedido')

@section('content')
<div class="container mt-5">
    <h4>Detalle del Pedido #{{ $pedido->id_pedido }}</h4>
    <p><strong>Fecha:</strong> {{ $pedido->fecha }}</p>
    <p><strong>Total:</strong> S/ {{ $pedido->total }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>S/ {{ $detalle->precio_unitario }}</td>
                    <td>S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">Volver al historial</a>
</div>
@endsection
