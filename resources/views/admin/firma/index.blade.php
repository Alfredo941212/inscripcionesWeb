@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Verificador de Firma Digital</h3>

    @if(session('resultado'))
        <div class="alert alert-info">{{ session('resultado') }}</div>
    @endif

    <form method="POST">
        @csrf
        <div class="mb-3">
            <label>Datos firmados</label>
            <textarea class="form-control" name="data" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label>Firma digital (Base64)</label>
            <textarea class="form-control" name="signature" rows="3" required></textarea>
        </div>

        <button class="btn btn-primary">Verificar Firma</button>
    </form>
</div>
@endsection
