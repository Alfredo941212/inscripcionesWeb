@extends('layouts.app')

@section('content')
<div class="container col-md-10">

    <h2 class="fw-bold text-primary mb-4">Simulación del Protocolo IPSEC</h2>

    <form action="{{ route('admin.ipsec.send') }}" method="POST">
        @csrf
        <button class="btn btn-dark mb-3">
            <i class="bi bi-shield-lock"></i> Ejecutar prueba IPSEC
        </button>
    </form>

    @if(session('ipsec'))
    <div class="alert alert-success">
        <strong>{{ session('ipsec')['resultado'] }}</strong><br>
        Modo: {{ session('ipsec')['mode'] }}<br>
        Cifrado: {{ session('ipsec')['encryption'] }}<br>
        Integridad: {{ session('ipsec')['auth'] }}<br>
        IP Origen: {{ session('ipsec')['ip_origen'] }}<br>
        IP Destino: {{ session('ipsec')['ip_destino'] }}<br>
    </div>
    @endif

    <h4 class="mt-4">Historial de pruebas IPSEC</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Modo</th>
                <th>Cifrado</th>
                <th>Auth</th>
                <th>IP Origen</th>
                <th>IP Destino</th>
                <th>Resultado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->mode }}</td>
                <td>{{ $log->encryption }}</td>
                <td>{{ $log->auth }}</td>
                <td>{{ $log->ip_origen }}</td>
                <td>{{ $log->ip_destino }}</td>
                <td>{{ $log->resultado }}</td>
                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
