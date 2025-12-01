@extends('layouts.app')

@section('title', 'Panel administrativo')

@section('content')
<div class="container">
    <h1 class="h4 fw-bold text-primary mb-4">Panel administrativo</h1>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-1">Total registros</h6>
                    <div class="display-6 fw-bold text-primary">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-1">Pendientes</h6>
                    <div class="display-6 fw-bold text-warning">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-1">Aceptados</h6>
                    <div class="display-6 fw-bold text-success">{{ $stats['accepted'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-1">Rechazados</h6>
                    <div class="display-6 fw-bold text-danger">{{ $stats['rejected'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold text-uppercase">Busqueda</label>
                    <input type="text" name="search" class="form-control"
                           value="{{ $filters['search'] ?? '' }}" placeholder="Nombre, correo o numero">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold text-uppercase">Disciplina</label>
                    <select name="discipline" class="form-select">
                        <option value="">Todas</option>
                        @foreach($disciplines as $discipline)
                            <option value="{{ $discipline->id }}" @selected(($filters['discipline'] ?? '') == $discipline->id)>
                                {{ $discipline->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-semibold text-uppercase">Genero</label>
                    <select name="gender" class="form-select">
                        <option value="">Todos</option>
                        <option value="femenil" @selected(($filters['gender'] ?? '') === 'femenil')>Femenil</option>
                        <option value="varonil" @selected(($filters['gender'] ?? '') === 'varonil')>Varonil</option>
                        <option value="mixto" @selected(($filters['gender'] ?? '') === 'mixto')>Mixto</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-semibold text-uppercase">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pendiente</option>
                        <option value="accepted" @selected(($filters['status'] ?? '') === 'accepted')>Aceptado</option>
                        <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-2"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                        <i class="bi bi-arrow-repeat"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">Estado de disciplinas</h5>
                    @php
                        $disciplineTotals = [
                            'pending' => $disciplineStatusSummary['pending'] ?? 0,
                            'accepted' => $disciplineStatusSummary['accepted'] ?? 0,
                            'rejected' => $disciplineStatusSummary['rejected'] ?? 0,
                        ];
                    @endphp
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pendientes
                            <span class="badge bg-warning text-dark">{{ $disciplineTotals['pending'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Aceptadas
                            <span class="badge bg-success">{{ $disciplineTotals['accepted'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Rechazadas
                            <span class="badge bg-danger">{{ $disciplineTotals['rejected'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">Disciplinas pendientes por revisar</h5>
                    @if($pendingDisciplineRequests->isEmpty())
                        <p class="text-muted mb-0">No hay solicitudes pendientes de disciplinas.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Participante</th>
                                        <th>Disciplina</th>
                                        <th>Seleccionada</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingDisciplineRequests as $request)
                                        <tr>
                                            <td>
                                                <strong>{{ $request->participant_name }}</strong>
                                                <small class="d-block text-muted"># {{ $request->worker_number }}</small>
                                            </td>
                                            <td>
                                                {{ $request->discipline_name }}
                                                <small class="d-block text-muted text-uppercase">
                                                    {{ $request->category }} - {{ $request->gender }}
                                                </small>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($request->selected_at)->format('d/m/Y H:i') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.participants.show', $request->participant_profile_id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Revisar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h5 fw-semibold mb-0">Inscripciones</h2>
        <div class="btn-group">
            <a href="{{ route('admin.reports.download', array_merge(['format' => 'xlsx'], request()->query())) }}"
               class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Generar Excel
            </a>
            <a href="{{ route('admin.reports.download', array_merge(['format' => 'pdf'], request()->query())) }}"
               class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Generar PDF
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h6 text-primary fw-bold mb-3">Reportes generados</h2>
            @if($reports->isEmpty())
                <p class="text-muted mb-0">Aun no se han generado reportes para compartir con los supervisores.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Formato</th>
                                <th>Tamano</th>
                                <th>Generado</th>
                                <th>Por</th>
                                <th>Filtros aplicados</th>
                                <th class="text-end">Descargar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                @php
                                    $sizeLabel = $report->size_bytes
                                        ? number_format($report->size_bytes / 1024, 1) . ' KB'
                                        : 'N/D';
                                @endphp
                                <tr>
                                    <td>{{ $report->name }}</td>
                                    <td><span class="badge bg-secondary text-uppercase">{{ $report->format }}</span></td>
                                    <td>{{ $sizeLabel }}</td>
                                    <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $report->author?->name ?? 'Sistema' }}</td>
                                    <td>
                                        @if(!empty($report->filters))
                                            <ul class="list-inline mb-0 small">
                                                @foreach($report->filters as $key => $value)
                                                    <li class="list-inline-item">
                                                        <span class="badge bg-light text-dark text-uppercase">
                                                            {{ $key }}: {{ $value }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted small">Sin filtros</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.reports.exports.download', $report) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Trabajador</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Disciplinas</th>
                        <th>Estado</th>
                        <th>Actualizado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $participant)
                        <tr>
                            <td>{{ $participants->firstItem() + $loop->index }}</td>
                            <td>{{ $participant->user->worker_number ?? '-' }}</td>
                            <td>{{ $participant->user->name }}</td>
                            <td>{{ $participant->user->email }}</td>
                            <td>{{ $participant->user->phone ?? 'N/A' }}</td>
                            <td>
                                @if($participant->disciplines->isEmpty())
                                    <span class="badge bg-secondary">Sin seleccion</span>
                                @else
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($participant->disciplines as $discipline)
                                            <li>{{ $discipline->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = ['pending' => 'warning', 'accepted' => 'success', 'rejected' => 'danger'];
                                    $statusLabels = ['pending' => 'Pendiente', 'accepted' => 'Aceptado', 'rejected' => 'Rechazado'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$participant->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$participant->status] ?? $participant->status }}
                                </span>
                            </td>
                            <td>{{ $participant->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.participants.show', $participant) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No se encontraron registros con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $participants->links() }}
        </div>
    </div>
</div>
@endsection
