@extends('layouts.app')

@section('title', 'Panel de supervision')

@section('content')
<div class="container">
    <h1 class="h4 fw-bold text-primary mb-4">Panel de supervision</h1>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-semibold mb-1">Registros totales</h6>
                    <div class="display-6 fw-bold text-primary">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-semibold mb-1">Pendientes</h6>
                    <div class="display-6 fw-bold text-warning">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-semibold mb-1">Aceptados</h6>
                    <div class="display-6 fw-bold text-success">{{ $stats['accepted'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-semibold mb-1">Rechazados</h6>
                    <div class="display-6 fw-bold text-danger">{{ $stats['rejected'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h6 text-primary fw-bold mb-3">Reportes disponibles</h2>
            @if($reports->isEmpty())
                <p class="text-muted mb-0">En cuanto el administrador genere reportes, podras descargarlos desde este apartado.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Formato</th>
                                <th>Generado</th>
                                <th>Por</th>
                                <th>Descargar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>{{ $report->name }}</td>
                                    <td><span class="badge bg-secondary text-uppercase">{{ $report->format }}</span></td>
                                    <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $report->author?->name ?? 'Sistema' }}</td>
                                    <td>
                                        <a href="{{ route('supervisor.reports.exports.download', $report) }}" class="btn btn-outline-primary btn-sm">
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

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body table-responsive">
            <h2 class="h5 fw-semibold text-primary mb-3">Capacidad por disciplina</h2>
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Categoria</th>
                        <th>Genero</th>
                        <th>Capacidad maxima</th>
                        <th>Inscritos</th>
                        <th>Aceptados</th>
                        <th>Lugares disponibles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($disciplineStats as $discipline)
                        <tr>
                            <td>{{ $discipline->name }}</td>
                            <td class="text-capitalize">{{ $discipline->category }}</td>
                            <td class="text-capitalize">{{ $discipline->gender }}</td>
                            <td>{{ $discipline->max_capacity }}</td>
                            <td>{{ $discipline->total_participants }}</td>
                            <td>{{ $discipline->accepted_participants }}</td>
                            <td>
                                <span class="badge {{ $discipline->remaining_capacity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $discipline->remaining_capacity }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h5 fw-semibold text-primary mb-3">Inscripciones por dia</h2>
            @if($statusTimeline->isEmpty())
                <p class="text-muted mb-0">Aun no hay registros para mostrar.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($statusTimeline as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</span>
                            <span class="badge bg-primary">{{ $item->total }} registros</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
