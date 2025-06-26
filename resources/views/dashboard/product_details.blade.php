@extends('dashboard.layouts.dashboard')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4"><i class="bi bi-box-seam-fill"></i> Detalles del Producto</h2>

    <div class="card shadow-sm border-0 mb-4">
        <div class="row g-0">
            <div class="col-md-4 text-center p-3">
                <img src="{{ asset($producto->imagen) }}" class="img-fluid rounded shadow" alt="Imagen de {{ $producto->nombre }}">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h4 class="card-title">{{ $producto->nombre }}</h4>
                    <p class="card-text"><strong>Precio:</strong> <span class="text-success">S/ {{ number_format($producto->precio, 2) }}</span></p>
                    <p class="card-text"><strong>Descripción:</strong> {{ $producto->descripcion ?? 'Sin descripción' }}</p>
                    <p class="card-text"><strong>Oferta:</strong> 
                        @if ($producto->oferta)
                            <span class="badge bg-success">Sí</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </p>

                    <h6 class="mt-4">Lotes del producto:</h6>
                    @if($producto->lotes->isEmpty())
                        <p class="text-muted">No hay lotes registrados</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nro. Lote</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($producto->lotes as $lote)
                                    <tr>
                                        <td>{{ $lote->nro_lote }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lote->fecha_vencimiento)->format('d/m/Y') }}</td>
                                        <td>{{ $lote->cantidad }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('product.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Volver al listado
                        </a>

                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditProduct" aria-controls="offcanvasEditProduct">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas para edición -->
<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasEditProduct" aria-labelledby="offcanvasEditProductLabel" style="height: 70vh;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasEditProductLabel">
            <i class="bi bi-pencil-square me-1"></i> Editar Producto
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <form method="POST" action="{{ route('product.update', $producto->id_producto) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="precio" class="form-label">Precio (S/.)</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="oferta" name="oferta" value="1" {{ old('oferta', $producto->oferta) ? 'checked' : '' }}>
                        <label class="form-check-label" for="oferta">
                            ¿Está en oferta?
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="imagen" class="form-label">Cambiar Imagen (opcional)</label>
                    <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3 gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save-fill"></i> Guardar Cambios
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection