@extends('layouts.app')

@section('title', 'Mi Perfil - MarcosFarma')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg border-0 p-4 rounded-4 w-100" style="max-width: 500px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary mb-1">
                <i class="bi bi-person-circle me-2"></i> Mi Perfil
            </h2>
            <p class="text-muted mb-0">Actualiza tu información personal</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-center small d-flex align-items-center justify-content-center">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('perfil.actualizar') }}" method="POST" class="mt-3">
            @csrf

            {{-- Nombre --}}
            <div class="mb-3">
                <label for="nombre" class="form-label fw-semibold">Nombre</label>
                <input type="text" id="nombre" name="nombre"
                       class="form-control form-control-lg"
                       value="{{ old('nombre', $cliente->nombre) }}"
                       required maxlength="20" autofocus placeholder="Ingrese su nombre">
                @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Apellido --}}
            <div class="mb-3">
                <label for="apellido" class="form-label fw-semibold">Apellido</label>
                <input type="text" id="apellido" name="apellido"
                       class="form-control form-control-lg"
                       value="{{ old('apellido', $cliente->apellido) }}"
                       required maxlength="20" placeholder="Ingrese su apellido">
                @error('apellido')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Teléfono --}}
            <div class="mb-3">
                <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                <input type="text" id="telefono" name="telefono"
                       class="form-control form-control-lg"
                       value="{{ old('telefono', $cliente->telefono) }}"
                       maxlength="9" placeholder="Ingrese su teléfono">
                @error('telefono')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success w-100 fw-semibold py-2">
                <i class="bi bi-save me-2"></i> Actualizar Perfil
            </button>
        </form>
    </div>
</div>
@endsection