@extends('layouts.app')

@section('title', 'Inscripciones deportivas y culturales')

@section('content')
<div class="container">
    <div class="row align-items-center gy-4">
        <div class="col-lg-6">
            <h1 class="fw-bold text-primary mb-3">
                Evento Deportivo y Cultural 2025
            </h1>
            <p class="lead text-secondary">
                Regístrate y participa en las disciplinas deportivas y culturales que la universidad tiene preparadas para ti.
            </p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Registro seguro y controlado por número de trabajador.</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Sube tus documentos oficiales y da seguimiento al estado de validación.</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Elige hasta dos disciplinas con cupo limitado.</li>
            </ul>

            @guest
                <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus-fill me-2"></i>Crear cuenta
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                    </a>
                </div>
            @endguest
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <h5 class="text-primary fw-semibold mb-3">Disciplinas disponibles</h5>
                    <div class="row row-cols-1 row-cols-sm-2 g-3">
                        @foreach($disciplines as $discipline)
                            <div class="col">
                                <div class="border rounded-3 p-3 h-100 bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-semibold mb-0">{{ $discipline->name }}</h6>
                                        <span class="badge {{ $discipline->remaining_capacity > 0 ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
                                            {{ $discipline->remaining_capacity }} libres
                                        </span>
                                    </div>
                                    <small class="text-muted text-uppercase">{{ $discipline->category }} &middot; {{ $discipline->gender }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        Cupos limitados por disciplina. Asegura tu lugar completando tu registro.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
