@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-primary fw-bold">Perfil del Administrador</h2>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ asset('img/administrador.png') }}" alt="Administrador" class="img-fluid rounded-circle shadow" style="max-width: 150px;">
                </div>
                <div class="col-md-9">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nombre:</dt>
                        <dd class="col-sm-8 fw-semibold">{{ Auth::user()->nombre ?? 'Admin' }}</dd>

                        <dt class="col-sm-4">Correo:</dt>
                        <dd class="col-sm-8 fw-semibold">{{ Auth::user()->correo ?? 'admin@marcosfarma.com' }}</dd>

                        <dt class="col-sm-4">Rol:</dt>
                        <dd class="col-sm-8 fw-semibold">Administrador</dd>

                        <dt class="col-sm-4">Último acceso:</dt>
                        <dd class="col-sm-8 fw-semibold">{{ now()->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
