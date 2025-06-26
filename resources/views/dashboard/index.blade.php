@extends('dashboard.layouts.app')

@section('content')
    {{-- Contenido principal ajustado --}}
    <div class="container-fluid" style="margin-left: 250px; padding-top: 80px;">
        <div class="p-4">
            <h1>Bienvenido, {{ session('nombre') }}</h1>
            <p class="text-muted">Estás en el panel de administración.</p>

            <!-- Contenido del Dashboard -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Resumen del sistema</h5>
                    <p class="card-text">Aquí puedes gestionar productos, usuarios y más.</p>
                </div>
            </div>
        </div>
    </div>