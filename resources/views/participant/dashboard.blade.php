@extends('layouts.app')

@section('title', 'Panel del participante')

@section('content')
@php
    $statusLabels = [
        'pending' => 'Pendiente',
        'accepted' => 'Aceptado',
        'rejected' => 'Rechazado',
    ];
    $canManageDisciplines = $profile->status === 'accepted';
@endphp

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Estado del registro</h5>
                    <p class="card-text mb-1">
                        <span class="badge bg-{{ $profile->status === 'accepted' ? 'success' : ($profile->status === 'rejected' ? 'danger' : 'warning') }} text-uppercase">
                            {{ $statusLabels[$profile->status] ?? ucfirst($profile->status) }}
                        </span>
                    </p>
                    <p class="small text-muted mb-0">
                        @switch($profile->status)
                            @case('accepted')
                                Tu registro ha sido validado. Ya puedes seleccionar y gestionar tus disciplinas.
                                @break
                            @case('rejected')
                                Tu registro fue rechazado. Revisa las observaciones y actualiza tu informacion desde el modulo de perfil.
                                @break
                            @default
                                Estamos revisando tu informacion y documentos. Te avisaremos en cuanto el administrador valide tu registro.
                        @endswitch
                    </p>
                    @if($profile->status_notes)
                        <div class="alert alert-info mt-3 mb-0">
                            <strong>Observaciones:</strong>
                            <p class="mb-0">{{ $profile->status_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">Resumen de disciplinas</h5>

                    @if(!$canManageDisciplines)
                        <div class="alert alert-warning mb-0">
                            Una vez que el administrador acepte tu registro podras elegir hasta dos disciplinas.
                            Mientras tanto puedes revisar o actualizar tus datos desde <a href="{{ route('participant.profile.edit') }}" class="alert-link">Mi perfil</a>.
                        </div>
                    @elseif($profile->disciplines->isEmpty())
                        <div class="alert alert-warning mb-0">
                            Aun no has seleccionado disciplinas. Puedes elegir hasta dos desde el modulo
                            <a href="{{ route('participant.disciplines.index') }}" class="alert-link">Disciplinas</a>.
                        </div>
                    @else
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            @foreach($profile->disciplines as $discipline)
                                @php
                                    $selectedAt = $discipline->pivot->selected_at
                                        ? \Carbon\Carbon::parse($discipline->pivot->selected_at)
                                        : $discipline->pivot->created_at;
                                    $pivotStatus = $discipline->pivot->status ?? 'pending';
                                    $pivotStatusLabels = ['pending' => 'Pendiente', 'accepted' => 'Aceptada', 'rejected' => 'Rechazada'];
                                    $pivotStatusColors = ['pending' => 'warning', 'accepted' => 'success', 'rejected' => 'danger'];
                                @endphp
                                <div class="col">
                                    <div class="border rounded-3 p-3 h-100">
                                        <h6 class="fw-semibold mb-1">{{ $discipline->name }}</h6>
                                        <small class="text-muted text-uppercase d-block">
                                            {{ $discipline->category }} - {{ $discipline->gender }}
                                        </small>
                                        <small class="text-muted">Registrado el {{ optional($selectedAt)->format('d/m/Y H:i') }}</small>
                                        <span class="badge bg-{{ $pivotStatusColors[$pivotStatus] ?? 'secondary' }} mt-2 text-uppercase">
                                            {{ $pivotStatusLabels[$pivotStatus] ?? ucfirst($pivotStatus) }}
                                        </span>
                                        @if($discipline->pivot->status_notes)
                                            <small class="d-block text-muted mt-1">
                                                <strong>Notas:</strong> {{ $discipline->pivot->status_notes }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('participant.disciplines.index') }}" class="btn btn-outline-primary btn-sm">
                                Gestionar disciplinas
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
