@extends('layouts.app')

@section('title', 'Nueva contraseña')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4 rounded-4" style="max-width: 420px; width: 100%;">
        <h4 class="text-center mb-3 text-primary">
            <i class="bi bi-shield-lock"></i> Nueva contraseña
        </h4>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
                <label>Nueva contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-success w-100">Actualizar contraseña</button>
        </form>
    </div>
</div>
@endsection
