<?php

namespace App\Http\Controllers\Auth;

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

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'worker_number' => ['required', 'string', 'max:50', 'unique:users,worker_number'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birthdate' => ['required', 'date', 'before_or_equal:today'],
            'curp' => ['required', 'string', 'size:18'],
            'seniority_years' => ['required', 'integer', 'min:0', 'max:60'],
            'constancia' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'cfdi' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'worker_number' => $data['worker_number'],
            'phone' => $data['phone'] ?? null,
            'role' => 'participant',
            'password' => Hash::make($data['password']),
        ]);

        $constanciaPath = $request->file('constancia')->store('documentos/constancias', 'public');
        $cfdiPath = $request->file('cfdi')->store('documentos/cfdi', 'public');
        $photoPath = $request->file('photo')->store('documentos/fotos', 'public');

        ParticipantProfile::create([
            'user_id' => $user->id,
            'birthdate' => $data['birthdate'],
            'curp' => strtoupper($data['curp']),
            'seniority_years' => (int) $data['seniority_years'],
            'constancia_path' => $constanciaPath,
            'cfdi_path' => $cfdiPath,
            'photo_path' => $photoPath,
            'status' => 'pending',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()
            ->route('participant.dashboard')
            ->with('success', 'Tu registro fue enviado para validacion. Te avisaremos cuando el administrador apruebe tu cuenta.');
    }
}
