@extends('layouts.app')

@section('title', 'Inicio | MarcosFarma')

@section('content')
    <div class="container d-flex flex-column align-items-center justify-content-center text-center mt-5">

        <div class="mb-4">
            <h1 class="fw-bold text-primary">MarcosFarma</h1>
            <p class="lead text-secondary">Tu farmacia de confianza</p>
        </div>

        <a href="{{ url('/catalog') }}" class="btn btn-lg btn-success fw-semibold px-4 py-2">
            <i class="bi bi-bag-plus me-2"></i> Ver Productos
        </a>
        
    </div>
@endsection