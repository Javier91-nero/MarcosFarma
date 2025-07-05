@extends('layouts.app')

@section('title', 'Factura - MarcosFarma')

@section('content')
<div class="container py-5">
    <div class="bg-white shadow-sm p-4 rounded-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-primary mb-1">Factura de Compra</h4>
                <div class="text-muted small">N° Pedido: {{ $pedido->id_pedido }}</div>
                <div class="text-muted small">Fecha: {{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y H:i') }}</div>
            </div>
            <div class="text-end mt-3 mt-md-0">
                <img src="{{ asset('img/logo_marcosfarma.png') }}" alt="Logo" style="max-height: 60px;">
            </div>
        </div>

        <hr>

        {{-- Información del Cliente --}}
        <div class="mb-4">
            <h5 class="text-secondary">Datos del Cliente</h5>
            <ul class="list-unstyled">
                <li><strong>Nombre:</strong> {{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</li>
                <li><strong>DNI:</strong> {{ $pedido->cliente->dni }}</li>
                <li><strong>Teléfono:</strong> {{ $pedido->cliente->telefono }}</li>
                <li><strong>Correo:</strong> {{ $pedido->cliente->correo }}</li>
            </ul>
        </div>

        {{-- Detalle de Productos --}}
        <div class="table-responsive mb-4">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($pedido->detalles as $detalle)
                        @php
                            $subtotal = $detalle->precio_unitario * $detalle->cantidad;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>S/ {{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-light fw-bold">
                        <td colspan="3" class="text-end">Total:</td>
                        <td>S/ {{ number_format($total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between flex-column flex-md-row align-items-center">
            <a href="{{ route('catalog') }}" class="btn btn-outline-secondary mb-3 mb-md-0">
                <i class="bi bi-arrow-left-circle me-1"></i> Seguir Comprando
            </a>
            <button onclick="window.print();" class="btn btn-primary">
                <i class="bi bi-printer me-1"></i> Imprimir Factura
            </button>
        </div>
    </div>
</div>

{{-- SweetAlert2: animación de confirmación de compra --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (!sessionStorage.getItem('factura_mostrada')) {
            Swal.fire({
                title: '¡Pago exitoso!',
                text: 'Tu pedido ha sido registrado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 5000,
                timerProgressBar: true
            });
            sessionStorage.setItem('factura_mostrada', '1');
        }
    });
</script>
@endsection