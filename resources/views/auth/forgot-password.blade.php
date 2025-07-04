@extends('layouts.app')

@section('title', '¿Olvidaste tu contraseña?')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4 rounded-4" style="max-width: 420px; width: 100%;">
        <h4 class="text-center mb-3 text-primary">
            <i class="bi bi-envelope-lock"></i> Recuperar contraseña
        </h4>

        @if(session('status'))
            <div class="alert alert-success small text-center">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" required>
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary w-100">Enviar enlace</button>
        </form>
    </div>
</div>
@endsection
