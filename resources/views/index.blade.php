@extends('layouts.app')

@section('title', 'Inicio | MarcosFarma')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center text-center mt-5">

    <div class="mb-4">
        <h1 class="fw-bold text-primary">MarcosFarma</h1>
        <p class="lead text-secondary">Tu farmacia de confianza</p>
    </div>

    <div id="marcosCarousel" class="carousel slide mb-4" data-bs-ride="carousel" style="max-width: 600px; width: 100%;">
        <div class="carousel-inner rounded shadow">
            <div class="carousel-item active">
                <img src="{{ asset('images/carrusel1.jpg') }}" class="d-block w-100" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carrusel2.jpg') }}" class="d-block w-100" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carrusel3.jpg') }}" class="d-block w-100" alt="Imagen 3">
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#marcosCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#marcosCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-secondary">Bienvenido a MarcosFarma</h2>
        <p class="text-muted">Explora nuestros productos y encuentra lo que necesitas para tu salud y bienestar.</p>

    <a href="{{ url('/catalog') }}" class="btn btn-lg btn-success fw-semibold px-4 py-2">
        <i class="bi bi-bag-plus me-2"></i> Ver Productos
    </a>

</div>
@endsection