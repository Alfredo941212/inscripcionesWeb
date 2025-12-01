@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <h1 class="h5 text-primary fw-bold mb-4">Datos personales y documentos</h1>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('participant.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre completo *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Número de trabajador</label>
                                <input type="text" class="form-control" value="{{ $user->worker_number }}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo institucional</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Teléfono de contacto</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Fecha de nacimiento</label>
                                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                                       value="{{ old('birthdate', optional($profile->birthdate)->format('Y-m-d')) }}">
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">CURP</label>
                                <input type="text" name="curp" class="form-control @error('curp') is-invalid @enderror"
                                       value="{{ old('curp', $profile->curp) }}" maxlength="18">
                                @error('curp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Antigüedad (años)</label>
                                <input type="number" name="seniority_years" min="0" class="form-control @error('seniority_years') is-invalid @enderror"
                                       value="{{ old('seniority_years', $profile->seniority_years) }}">
                                @error('seniority_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Constancia laboral (PDF/JPG)</label>
                                <input type="file" name="constancia" class="form-control @error('constancia') is-invalid @enderror"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                @error('constancia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($profile->constancia_path)
                                    <a href="{{ Storage::disk('public')->url($profile->constancia_path) }}" target="_blank" class="small d-block mt-1">
                                        Ver archivo actual
                                    </a>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">CFDI / Recibo (PDF/JPG)</label>
                                <input type="file" name="cfdi" class="form-control @error('cfdi') is-invalid @enderror"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                @error('cfdi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($profile->cfdi_path)
                                    <a href="{{ Storage::disk('public')->url($profile->cfdi_path) }}" target="_blank" class="small d-block mt-1">
                                        Ver archivo actual
                                    </a>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Fotografía reciente (JPG/PNG)</label>
                                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror"
                                       accept=".jpg,.jpeg,.png">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($profile->photo_path)
                                    <img src="{{ Storage::disk('public')->url($profile->photo_path) }}" class="rounded-circle border mt-2"
                                         style="width: 80px; height: 80px; object-fit: cover;" alt="Foto del participante">
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Guardar cambios
                            </button>
                            <a href="{{ route('participant.dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
