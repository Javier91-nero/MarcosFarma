@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center text-primary">🧾 Confirmación de Pedido</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm p-4">
        <h5 class="mb-3">Resumen del carrito</h5>
        <ul class="list-group mb-3">
            @foreach ($carrito as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item['nombre'] }} x {{ $item['cantidad'] }}
                    <span class="fw-bold">S/ {{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
                </li>
            @endforeach
        </ul>

        <form method="POST" action="{{ route('checkout.procesar') }}">
            @csrf

            <div class="mb-3">
                <label for="numero_tarjeta" class="form-label">Número de tarjeta</label>
                <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" required placeholder="**** **** **** 1234">
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-credit-card"></i> Confirmar y Pagar
            </button>
        </form>
    </div>
</div>
@endsection
