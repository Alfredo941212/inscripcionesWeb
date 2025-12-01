@extends('layouts.app')

@section('title', 'Detalle del participante')

@section('content')
<div class="container">
    @php
        $disciplineReviewers = $disciplineReviewers ?? collect();
    @endphp
    <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-decoration-none mb-3">
        <i class="bi bi-arrow-left-circle me-1"></i> Volver al panel
    </a>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($participant->photo_path)
                            <img src="{{ Storage::disk('public')->url($participant->photo_path) }}"
                                 alt="Foto" class="rounded-circle border"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 120px; height: 120px;">
                                <i class="bi bi-person fs-1 text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-0">{{ $participant->user->name }}</h5>
                    <p class="text-muted mb-0">{{ $participant->user->email }}</p>
                    <p class="text-muted mb-0"># {{ $participant->user->worker_number }}</p>
                    <p class="text-muted mb-0">{{ $participant->user->phone ?? 'Sin telefono' }}</p>

                    <hr>

                    @php
                        $statusColors = ['pending' => 'warning', 'accepted' => 'success', 'rejected' => 'danger'];
                        $statusLabels = ['pending' => 'Pendiente', 'accepted' => 'Aceptado', 'rejected' => 'Rechazado'];
                    @endphp
                    <p class="mb-1 text-muted text-uppercase small">Estado del registro</p>
                    <span class="badge bg-{{ $statusColors[$participant->status] ?? 'secondary' }}">
                        {{ $statusLabels[$participant->status] ?? $participant->status }}
                    </span>

                    @if($participant->reviewer)
                        <p class="small text-muted mt-2 mb-0">
                            Revisado por {{ $participant->reviewer->name }}
                            el {{ optional($participant->reviewed_at)->format('d/m/Y H:i') }}
                        </p>
                    @endif

                    @if($participant->status_notes)
                        <div class="alert alert-info mt-3 mb-0 text-start">
                            <strong>Notas:</strong>
                            <p class="mb-0">{{ $participant->status_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">Informacion general</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Fecha de nacimiento:</strong> {{ optional($participant->birthdate)->format('d/m/Y') ?? 'No registrada' }}</p>
                            <p class="mb-1"><strong>CURP:</strong> {{ $participant->curp ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Antiguedad:</strong> {{ $participant->seniority_years }} anos</p>
                            <p class="mb-1"><strong>Ultima actualizacion:</strong> {{ $participant->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">Documentos</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="fw-semibold mb-1">Constancia laboral</p>
                            @if($participant->constancia_path)
                                <a href="{{ Storage::disk('public')->url($participant->constancia_path) }}"
                                   target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-text me-1"></i> Ver constancia
                                </a>
                            @else
                                <span class="text-muted">No cargada</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="fw-semibold mb-1">CFDI / Recibo</p>
                            @if($participant->cfdi_path)
                                <a href="{{ Storage::disk('public')->url($participant->cfdi_path) }}"
                                   target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-text me-1"></i> Ver CFDI
                                </a>
                            @else
                                <span class="text-muted">No cargado</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">Disciplinas seleccionadas</h5>
                    @if(session('discipline_success'))
                        <div class="alert alert-success">{{ session('discipline_success') }}</div>
                    @endif

                    @if($participant->disciplines->isEmpty())
                        <p class="text-muted mb-0">Sin disciplinas asignadas.</p>
                    @else
                        <div class="row row-cols-1 row-cols-lg-2 g-3">
                            @foreach($participant->disciplines as $discipline)
                                @php
                                    $selectedAt = $discipline->pivot->selected_at
                                        ? \Carbon\Carbon::parse($discipline->pivot->selected_at)
                                        : $discipline->pivot->created_at;
                                    $pivotStatus = $discipline->pivot->status ?? 'pending';
                                    $pivotStatusNotes = $discipline->pivot->status_notes;
                                    $pivotReviewedAt = $discipline->pivot->reviewed_at
                                        ? \Carbon\Carbon::parse($discipline->pivot->reviewed_at)->format('d/m/Y H:i')
                                        : null;
                                    $pivotReviewer = $disciplineReviewers[$discipline->pivot->reviewed_by] ?? null;
                                    $disciplineStatusColors = ['pending' => 'warning', 'accepted' => 'success', 'rejected' => 'danger'];
                                    $disciplineStatusLabels = ['pending' => 'Pendiente', 'accepted' => 'Aceptada', 'rejected' => 'Rechazada'];
                                @endphp
                                <div class="col">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $discipline->name }}</h6>
                                            <small class="text-muted text-uppercase d-block">
                                                {{ $discipline->category }} - {{ $discipline->gender }}
                                            </small>
                                            <small class="text-muted d-block">
                                                Registrado el {{ optional($selectedAt)->format('d/m/Y H:i') }}
                                            </small>
                                            <span class="badge bg-{{ $disciplineStatusColors[$pivotStatus] ?? 'secondary' }} mt-2">
                                                {{ $disciplineStatusLabels[$pivotStatus] ?? ucfirst($pivotStatus) }}
                                            </span>
                                            @if($pivotReviewedAt)
                                                <small class="text-muted d-block mt-1">
                                                    Revisado {{ $pivotReviewedAt }}
                                                    @if($pivotReviewer)
                                                        por {{ $pivotReviewer }}
                                                    @endif
                                                </small>
                                            @endif
                                            @if($pivotStatusNotes)
                                                <small class="d-block text-muted mt-1">
                                                    <strong>Notas:</strong> {{ $pivotStatusNotes }}
                                                </small>
                                            @endif
                                        </div>
                                        <form method="POST" action="{{ route('admin.participants.disciplines.update', [$participant, $discipline]) }}" class="mt-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-2">
                                                <label class="form-label small text-uppercase text-muted mb-1">Estado de la disciplina</label>
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="pending" @selected($pivotStatus === 'pending')>Pendiente</option>
                                                    <option value="accepted" @selected($pivotStatus === 'accepted')>Aceptada</option>
                                                    <option value="rejected" @selected($pivotStatus === 'rejected')>Rechazada</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <textarea name="status_notes" rows="2" class="form-control form-control-sm" placeholder="Notas opcionales">{{ $pivotStatusNotes }}</textarea>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-primary btn-sm">
                                                    <i class="bi bi-save me-1"></i> Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Actualizar estado del registro</h5>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.participants.status', $participant) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nuevo estado</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" @selected($participant->status === 'pending')>Pendiente</option>
                                    <option value="accepted" @selected($participant->status === 'accepted')>Aceptado</option>
                                    <option value="rejected" @selected($participant->status === 'rejected')>Rechazado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Notas para el participante</label>
                                <textarea name="status_notes" rows="3" class="form-control @error('status_notes') is-invalid @enderror"
                                          placeholder="Opcional: agrega comentarios u observaciones">{{ old('status_notes', $participant->status_notes) }}</textarea>
                                @error('status_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i> Guardar estado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
