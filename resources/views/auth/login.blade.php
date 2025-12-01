@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <h1 class="h4 text-primary fw-bold mb-4 text-center">Acceder al sistema</h1>

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Correo institucional</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contraseña</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Mantener sesión iniciada</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                            </button>
                              {{-- LOGIN CON GOOGLE --}}
                            <a href="{{ route('oauth.google') }}" class="btn btn-danger">
                                <i class="bi bi-google me-2"></i> Iniciar sesión con Google
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                ¿Aún no tienes cuenta? Regístrate
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
