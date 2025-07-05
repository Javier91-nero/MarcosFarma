@extends('layouts.app')

@section('title', 'Mi Carrito - MarcosFarma')

@section('content')
<div class="container py-4">
    <h2 class="text-center text-primary mb-4">
        <i class="bi bi-cart-check me-2"></i>Mi Carrito
    </h2>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
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
                        <th>Subtotal</th>
                        <th>Eliminar</th>
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
                            <td>
                                <img src="{{ asset($item['imagen']) }}" alt="{{ $item['nombre'] }}"
                                     class="img-fluid" style="max-width: 80px;">
                            </td>
                            <td>{{ $item['nombre'] }}</td>
                            <td>S/ {{ number_format($item['precio'], 2) }}</td>
                            <td>
                                <input type="number"
                                       class="form-control form-control-sm mx-auto cantidad-input"
                                       style="width: 70px;"
                                       min="1"
                                       value="{{ $item['cantidad'] }}"
                                       data-id="{{ $item['id'] }}">
                            </td>
                            <td>S/ {{ number_format($subtotal, 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.eliminar') }}" method="POST" onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    <input type="hidden" name="id_producto" value="{{ $item['id'] }}">
                                    <button class="btn btn-sm btn-outline-danger">
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

        <div class="d-flex flex-column flex-md-row justify-content-end gap-2">
            <a href="{{ url('/catalog') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Seguir Comprando
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPago">
                <i class="bi bi-credit-card-2-front me-1"></i> Proceder al Pago
            </button>
        </div>

        {{-- Modal de Pago --}}
        <div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-4">
                    <form method="POST" action="{{ route('checkout.realizar') }}" onsubmit="return validarPago();">
                        @csrf
                        <div class="modal-header bg-primary text-white rounded-top-4">
                            <h5 class="modal-title" id="modalPagoLabel">
                                <i class="bi bi-credit-card-2-back me-2"></i>Datos de Pago
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="numero_tarjeta" class="form-label">Número de Tarjeta</label>
                                <input type="text" name="numero_tarjeta" id="numero_tarjeta"
                                       class="form-control" maxlength="16"
                                       required placeholder="1234567812345678"
                                       pattern="\d{16}" inputmode="numeric">
                            </div>
                            <input type="hidden" name="metodo_pago" value="tarjeta"> {{-- Campo necesario --}}
                            <input type="hidden" name="total" value="{{ $total }}">
                            <div class="alert alert-info small">
                                Usa un número de tarjeta ficticio de 16 dígitos para pruebas (p. ej. 1234123412341234).
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" id="btnPagar">
                                <i class="bi bi-check-circle me-1"></i> Confirmar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Script AJAX para actualizar cantidad automáticamente --}}
<script>
document.querySelectorAll('.cantidad-input').forEach(input => {
    input.addEventListener('change', () => {
        const id_producto = input.dataset.id;
        const cantidad = parseInt(input.value);

        fetch("{{ route('carrito.actualizar') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id_producto, cantidad })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "ok") {
                location.reload();
            } else if (data.status === "error") {
                alert(data.message);
                input.value = data.disponible ?? 1;
            }
        })
        .catch(() => {
            alert("Ocurrió un error al actualizar la cantidad.");
        });
    });
});

// Validación rápida del número de tarjeta
function validarPago() {
    const numero = document.getElementById('numero_tarjeta').value.trim();
    const regex = /^\d{16}$/;
    if (!regex.test(numero)) {
        alert('El número de tarjeta debe contener exactamente 16 dígitos.');
        return false;
    }

    document.getElementById('btnPagar').disabled = true;
    document.getElementById('btnPagar').innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Procesando...';
    return true;
}
</script>
@endsection
