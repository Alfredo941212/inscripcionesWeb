@extends('layouts.app')

@section('title', 'Seleccionar disciplinas')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 mb-lg-0">
                <div class="card-body">
                    <h5 class="card-title text-primary">Mis disciplinas</h5>
                    @if($profile->disciplines->isEmpty())
                        <p class="text-muted mb-0">Aún no has seleccionado disciplinas.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($profile->disciplines as $discipline)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        {{ $discipline->name }}
                                        <small class="text-muted d-block">{{ $discipline->category }} · {{ $discipline->gender }}</small>
                                    </span>
                                    <form method="POST" action="{{ route('participant.disciplines.destroy', $discipline) }}" onsubmit="return confirm('¿Deseas quitar esta disciplina?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                        <p class="small text-muted mt-2 mb-0">
                            Puedes elegir hasta dos disciplinas. Si retiras una, podrás seleccionar otra disponible.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">Disciplinas disponibles</h5>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Disciplina</th>
                                    <th>Categoría</th>
                                    <th>Género</th>
                                    <th>Cupo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disciplines as $discipline)
                                    <tr class="{{ $discipline->remaining_capacity === 0 ? 'table-light' : '' }}">
                                        <td>{{ $discipline->name }}</td>
                                        <td class="text-capitalize">{{ $discipline->category }}</td>
                                        <td class="text-capitalize">{{ $discipline->gender }}</td>
                                        <td>
                                            <span class="badge {{ $discipline->remaining_capacity > 0 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $discipline->remaining_capacity }} lugares disponibles
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($discipline->is_selected)
                                                <span class="badge bg-primary">Seleccionada</span>
                                            @elseif($discipline->remaining_capacity === 0 || $profile->disciplines->count() >= 2)
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    No disponible
                                                </button>
                                            @else
                                                <form method="POST" action="{{ route('participant.disciplines.store') }}">
                                                    @csrf
                                                    <input type="hidden" name="discipline_id" value="{{ $discipline->id }}">
                                                    <button class="btn btn-outline-primary btn-sm">
                                                        Seleccionar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
