@extends('layouts.app')

@section('title', 'Registro de Cliente')

@section('content')
<div class="container my-5">
    <div class="mx-auto p-4 shadow rounded-4 bg-white" style="max-width: 480px;">

        <h2 class="text-center mb-4 fw-bold text-primary">
            Registro de Cliente
        </h2>

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

        <form action="{{ route('registro.registrar') }}" method="POST" novalidate>
            @csrf

            {{-- Nombre y Apellido --}}
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="nombre" class="form-label fw-semibold">Nombre Completo</label>
                    <input
                        type="text" id="nombre" name="nombre"
                        class="form-control form-control-lg"
                        required autofocus
                        value="{{ old('nombre') }}"
                        placeholder="Nombre"
                        oninput="validarTextoSinNumeros(this, 'nombreError')">
                    <small id="nombreError" class="text-danger d-none">No se permiten números en el nombre.</small>
                </div>

                <div class="col-12 col-md-6">
                    <label for="apellido" class="form-label fw-semibold">Apellido Completo</label>
                    <input
                        type="text" id="apellido" name="apellido"
                        class="form-control form-control-lg"
                        required
                        value="{{ old('apellido') }}"
                        placeholder="Apellido"
                        oninput="validarTextoSinNumeros(this, 'apellidoError')">
                    <small id="apellidoError" class="text-danger d-none">No se permiten números en el apellido.</small>
                </div>
            </div>

            {{-- DNI y Teléfono --}}
            <div class="row g-3 mt-3">
                <div class="col-12 col-md-6">
                    <label for="dni" class="form-label fw-semibold">DNI</label>
                    <input
                        type="text" id="dni" name="dni" maxlength="8"
                        class="form-control form-control-lg"
                        required
                        value="{{ old('dni') }}"
                        placeholder="DNI"
                        oninput="validarSoloNumeros(this, 8, 'dniError')">
                    <small id="dniError" class="text-danger d-none">Solo números, máximo 8 dígitos.</small>
                </div>

                <div class="col-12 col-md-6">
                    <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                    <input
                        type="text" id="telefono" name="telefono" maxlength="9"
                        class="form-control form-control-lg"
                        value="{{ old('telefono') }}"
                        placeholder="Teléfono"
                        oninput="validarSoloNumeros(this, 9, 'telefonoError')">
                    <small id="telefonoError" class="text-danger d-none">Solo números, máximo 9 dígitos.</small>
                </div>
            </div>

            {{-- Correo --}}
            <div class="mb-3 mt-3">
                <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                <input
                    type="email" id="correo" name="correo"
                    class="form-control form-control-lg"
                    required
                    value="{{ old('correo') }}"
                    placeholder="ejemplo@correo.com">
            </div>

            {{-- Contraseña y Confirmación --}}
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input
                        type="password" id="password" name="password"
                        class="form-control form-control-lg"
                        required
                        placeholder="Contraseña"
                        oninput="evaluarSeguridadPassword(this.value)">
                    <small id="passwordStrength" class="form-text mt-1"></small>
                </div>

                <div class="col-12 col-md-6">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
                    <input
                        type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control form-control-lg"
                        required
                        placeholder="Confirmar Contraseña">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-semibold mt-4">Registrar</button>
        </form>

    </div>
</div>

@push('scripts')
<script src="{{ asset('js/registro.js') }}"></script>
@endpush
@endsection