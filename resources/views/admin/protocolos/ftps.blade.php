@extends('layouts.app')

@section('content')
<div class="container col-md-8">
    <h2 class="fw-bold">Simulación del Protocolo FTPS</h2>
    <hr>

    <div class="alert alert-success">
        <strong>Estado:</strong> {{ $resultado['status'] }}
    </div>

    <ul class="list-group">
        <li class="list-group-item"><strong>Servidor:</strong> {{ $resultado['server'] }}</li>
        <li class="list-group-item"><strong>Puerto:</strong> {{ $resultado['port'] }}</li>
        <li class="list-group-item"><strong>Acción:</strong> {{ $resultado['action'] }}</li>
        <li class="list-group-item"><strong>Fecha:</strong> {{ $resultado['timestamp'] }}</li>
    </ul>
</div>
@endsection
