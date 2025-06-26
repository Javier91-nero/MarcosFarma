@extends('layouts.app')

@section('title', 'Iniciar Sesión - MarcosFarma')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg border-0 p-4 rounded-4 w-100" style="max-width: 420px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary mb-1">
                <i class="bi bi-hospital me-2"></i>MarcosFarma
            </h2>
            <p class="text-muted mb-0">Bienvenido, por favor inicie sesión</p>
        </div>

        {{-- Mensaje de validación pendiente --}}
        @if(session('validacion_pendiente'))
            <div class="alert alert-warning text-center small d-flex align-items-center justify-content-center">
                <i class="bi bi-shield-exclamation me-2"></i>
                {{ session('validacion_pendiente') }}
            </div>
        @endif

        {{-- Mensaje de error --}}
        @if(session('error'))
            <div class="alert alert-danger text-center small d-flex align-items-center justify-content-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="mt-3">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" required autofocus placeholder="ejemplo@correo.com">
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control form-control-lg" required placeholder="Contraseña">
                @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
            </button>

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('registro.formulario') }}" class="text-decoration-none small">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
                <a href="#" class="text-decoration-none small">
                    <i class="bi bi-key"></i> ¿Olvidaste tu contraseña?
                </a>
            </div>
        </form>
    </div>
</div>
@endsection