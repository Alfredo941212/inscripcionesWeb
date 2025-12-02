@extends('layouts.app')

@section('title', 'Protocolos de Seguridad')

@section('content')
<div class="container">
    <h1 class="h4 fw-bold text-primary mb-4">Protocolos de Seguridad Implementados</h1>

    <div class="row g-3">
        @foreach($results as $protocol => $message)
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="text-primary text-uppercase">{{ strtoupper($protocol) }}</h5>
                        <p class="small text-muted">{{ $message }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
