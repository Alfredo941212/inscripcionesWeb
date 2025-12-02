<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Models\ParticipantProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use App\Models\Block;
use App\Http\Controllers\DigitalSignatureController;


class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    private function generarHashArchivo($archivo)
    {
        return hash_file("sha256", $archivo->getRealPath());
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'worker_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'curp' => ['required', 'string', 'max:18', 'min:18'],
            'seniority_years' => ['required', 'integer', 'min:0'],
            'password' => ['required', 'confirmed'],
            'constancia' => ['required', 'file', 'max:4096'],
            'cfdi' => ['required', 'file', 'max:4096'],
            'photo' => ['required', 'file', 'max:4096'],
        ]);

        // 1. Crear usuario
        $user = User::create([
            'name' => strtoupper($request->name),
            'worker_number' => $request->worker_number,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'curp' => strtoupper($request->curp),
            'seniority_years' => $request->seniority_years,
            'password' => Hash::make($request->password),
        ]);
        // Crear automáticamente el perfil del participante
        $user->participantProfile()->create([
            'birthdate' => $request->birthdate,
            'curp' => strtoupper($request->curp),
            'seniority_years' => $request->seniority_years,
            'status' => 'pending', // el Admin revisa este estado
        ]);

        // 2. Guardar documentos
        $rutaConstancia = $request->file('constancia')->store('documentos/constancia');
        $rutaCfdi = $request->file('cfdi')->store('documentos/cfdi');
        $rutaFoto = $request->file('photo')->store('documentos/foto');

        // 3. Calcular hash SHA-256 de cada documento
        $hashConstancia = $this->generarHashArchivo($request->file('constancia'));
        $hashCfdi = $this->generarHashArchivo($request->file('cfdi'));
        $hashFoto = $this->generarHashArchivo($request->file('photo'));

        // 4. Generar firma digital del registro
        $dataToSign = "Usuario={$user->name}; CURP={$user->curp}; Worker={$user->worker_number}; Fecha=" . now();
        $firmaDigital = DigitalSignatureController::signData($dataToSign);

        // 5. Guardar campos de seguridad en el usuario
        $user->hash_constancia = $hashConstancia;
        $user->hash_cfdi = $hashCfdi;
        $user->hash_foto = $hashFoto;
        $user->firma_digital = $firmaDigital;
        $user->save();

        // 6. Registrar bloque en Blockchain simulada
        Block::createBlock("Registro de usuario: {$user->name} con CURP {$user->curp}");

        // 7. Login automático + redirección
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Registro completado con seguridad digital.');
    }

}
