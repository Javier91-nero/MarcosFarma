@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-primary fw-bold">Registrar Nuevo Producto</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="mb-5 shadow-sm p-4 rounded bg-light border">
        @csrf
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label for="nombre" class="form-label fw-semibold">Nombre del producto <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej. Paracetamol" required value="{{ old('nombre') }}">
            </div>

            <div class="col-6 col-md-2">
                <label for="precio" class="form-label fw-semibold">Precio (S/.) <span class="text-danger">*</span></label>
                <input type="number" id="precio" name="precio" class="form-control" placeholder="0.00" step="0.01" min="0" required value="{{ old('precio') }}">
            </div>

            <div class="col-6 col-md-4">
                <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Detalles adicionales (opcional)" value="{{ old('descripcion') }}">
            </div>

            <div class="col-6 col-md-2">
                <label for="oferta" class="form-label fw-semibold">Oferta</label>
                <select id="oferta" name="oferta" class="form-select">
                    <option value="0" {{ old('oferta') == '0' ? 'selected' : '' }}>Sin oferta</option>
                    <option value="1" {{ old('oferta') == '1' ? 'selected' : '' }}>En oferta</option>
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label for="imagen" class="form-label fw-semibold">Imagen <span class="text-danger">*</span></label>
                <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*" required>
            </div>

            <div class="col-6 col-md-2">
                <label for="nro_lote" class="form-label fw-semibold">N° de lote <span class="text-danger">*</span></label>
                <input type="text" id="nro_lote" name="nro_lote" class="form-control" placeholder="12345" required value="{{ old('nro_lote') }}">
            </div>

            <div class="col-6 col-md-3">
                <label for="fecha_vencimiento" class="form-label fw-semibold">Fecha de vencimiento <span class="text-danger">*</span></label>
                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" class="form-control" required value="{{ old('fecha_vencimiento') }}">
            </div>

            <div class="col-6 col-md-3">
                <label for="cantidad" class="form-label fw-semibold">Cantidad <span class="text-danger">*</span></label>
                <input type="number" id="cantidad" name="cantidad" class="form-control" placeholder="0" min="1" required value="{{ old('cantidad') }}">
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">Guardar Producto</button>
        </div>
    </form>

    <hr>

    <h3 class="mb-3 text-secondary fw-semibold">Lista de productos registrados</h3>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped align-middle text-center table-hover mb-0">
            <thead class="table-primary text-primary">
                <tr>
                    <th>#</th>
                    <th class="text-start">Nombre</th>
                    <th>Precio (S/)</th>
                    <th class="text-start">Descripción</th>
                    <th>Oferta</th>
                    <th>Imagen</th>
                    <th>N° Lote</th>
                    <th>F. Vencimiento</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->id_producto }}</td>
                        <td class="text-start">{{ $producto->nombre }}</td>
                        <td>{{ number_format($producto->precio, 2) }}</td>
                        <td class="text-start">{{ $producto->descripcion ?? '—' }}</td>
                        <td>
                            @if($producto->oferta)
                                <span class="badge bg-success" data-bs-toggle="tooltip" title="Producto en oferta">Sí</span>
                            @else
                                <span class="badge bg-secondary" data-bs-toggle="tooltip" title="Producto sin oferta">No</span>
                            @endif
                        </td>
                        <td>
                            @if($producto->imagen)
                                <img src="{{ asset($producto->imagen) }}" alt="Imagen {{ $producto->nombre }}" class="rounded shadow-sm" width="60" height="60" style="object-fit: cover;">
                            @else
                                <span class="text-muted">Sin imagen</span>
                            @endif
                        </td>
                        <td>{{ $producto->lotes->first()->nro_lote ?? '—' }}</td>
                        <td>{{ $producto->lotes->first()->fecha_vencimiento ? \Carbon\Carbon::parse($producto->lotes->first()->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                        <td>{{ $producto->lotes->first()->cantidad ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted fst-italic">No hay productos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection