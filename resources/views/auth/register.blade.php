@extends('layouts.app')

@section('title', 'Crear cuenta')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <h1 class="h4 text-primary fw-bold mb-4 text-center">Registro de participante</h1>

                    <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre completo *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Numero de trabajador *</label>
                                <input type="text" name="worker_number" class="form-control @error('worker_number') is-invalid @enderror"
                                       value="{{ old('worker_number') }}" required>
                                @error('worker_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo institucional *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Telefono de contacto</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha de nacimiento *</label>
                                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                                       value="{{ old('birthdate') }}" required>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">CURP *</label>
                                <input type="text" name="curp" class="form-control text-uppercase @error('curp') is-invalid @enderror"
                                       value="{{ old('curp') }}" maxlength="18" minlength="18" required>
                                @error('curp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Antiguedad en años *</label>
                                <input type="number" name="seniority_years" class="form-control @error('seniority_years') is-invalid @enderror"
                                       value="{{ old('seniority_years') }}" min="0" max="60" required>
                                @error('seniority_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contrasena *</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Debe incluir mayusculas, minusculas, numeros y simbolos.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirmar contrasena *</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h2 class="h6 text-primary fw-bold mb-3">Documentos obligatorios</h2>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Constancia de servicios *</label>
                                    <input type="file" name="constancia" accept=".pdf,.jpg,.jpeg,.png"
                                           class="form-control @error('constancia') is-invalid @enderror" required>
                                    @error('constancia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">CFDI vigente *</label>
                                    <input type="file" name="cfdi" accept=".pdf,.jpg,.jpeg,.png"
                                           class="form-control @error('cfdi') is-invalid @enderror" required>
                                    @error('cfdi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Fotografia reciente *</label>
                                    <input type="file" name="photo" accept=".jpg,.jpeg,.png"
                                           class="form-control @error('photo') is-invalid @enderror" required>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Formatos permitidos: PDF, JPG o PNG (maximo 4 MB por archivo).</small>
                        </div>
                        <div class="mt-4">
                            <label class="form-check-label fw-semibold">
                                <input type="checkbox" name="sign_data" class="form-check-input" required>
                                Acepto firmar digitalmente mi registro para validar autenticidad de documentos
                            </label>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary">
                                <i class="bi bi-person-plus-fill me-2"></i>Crear cuenta
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                Ya tienes cuenta? Inicia sesion
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
