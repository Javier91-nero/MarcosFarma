@extends('layouts.app')

@section('content')
<div class="container my-5" style="max-width: 480px;">
    <h2 class="mb-4 text-center fw-bold text-primary">Verificación de Código</h2>

    {{-- Mensaje de éxito --}}
    @if(session('mensaje'))
        <div class="alert alert-success small text-center mb-3">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Errores --}}
    @if($errors->any())
        <div class="alert alert-danger small mb-3">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de confirmación --}}
    <form action="{{ route('registro.confirmar') }}" method="POST" class="mb-3">
        @csrf
        <input type="hidden" name="correo_temp" value="{{ old('correo_temp', $correoTemp ?? '') }}">

        <div class="mb-3">
            <label for="codigo" class="form-label fw-semibold">Ingrese el código de verificación</label>
            <input type="text" id="codigo" name="codigo" class="form-control form-control-lg" maxlength="8" required autofocus>
        </div>

        <button type="submit" class="btn btn-success w-100 fw-semibold">Confirmar</button>
    </form>

    {{-- Formulario de reenvío --}}
    <form action="{{ route('registro.reenviar') }}" method="POST">
        @csrf
        <input type="hidden" name="correo_temp" value="{{ old('correo_temp', $correoTemp ?? '') }}">

        <button type="submit" id="reenviar-btn" class="btn btn-outline-primary w-100 fw-semibold" disabled>
            Reenviar código (<span id="contador">20</span>s)
        </button>
    </form>
</div>

<script src="{{ asset('js/verificacion.js') }}"></script>
@endsection