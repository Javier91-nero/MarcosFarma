@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4 text-primary">Historial de Pedidos</h3>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @forelse($pedidos as $pedido)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5>Pedido #{{ $pedido->id_pedido }}</h5>
                <p>Fecha: {{ $pedido->fecha }} | Total: S/ {{ $pedido->total }}</p>
                <a href="{{ route('pedidos.show', $pedido->id_pedido) }}" class="btn btn-sm btn-outline-primary">Ver Detalles</a>

                <form method="POST" action="{{ route('pedidos.repetir', $pedido->id_pedido) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-success">Repetir Pedido</button>
                </form>
            </div>
        </div>
    @empty
        <p>No tienes pedidos todavía.</p>
    @endforelse
</div>
@endsection
