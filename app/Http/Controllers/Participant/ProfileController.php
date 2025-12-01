<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        $profile = $user->participantProfile;

        return view('participant.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->participantProfile;

        $validatedUser = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $validatedProfile = $request->validate([
            'birthdate' => ['nullable', 'date'],
            'curp' => ['nullable', 'string', 'size:18'],
            'seniority_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'constancia' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'cfdi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ]);

        $user->update($validatedUser);

        $updates = [
            'birthdate' => $validatedProfile['birthdate'] ?? $profile->birthdate,
            'curp' => isset($validatedProfile['curp'])
                ? strtoupper($validatedProfile['curp'])
                : $profile->curp,
            'seniority_years' => isset($validatedProfile['seniority_years'])
                ? (int) $validatedProfile['seniority_years']
                : $profile->seniority_years,
        ];

        $this->storeFileIfExists($request, $profile, 'constancia', 'constancias', $updates);
        $this->storeFileIfExists($request, $profile, 'cfdi', 'cfdi', $updates);
        $this->storeFileIfExists($request, $profile, 'photo', 'fotos', $updates);

        $profile->fill($updates);
        $wasDirty = $profile->isDirty();

        if ($wasDirty) {
            $profile->status = 'pending';
            $profile->reviewed_by = null;
            $profile->reviewed_at = null;
            $profile->status_notes = null;
        }

        $profile->save();

        return redirect()
            ->route('participant.profile.edit')
            ->with('success', 'Informacion actualizada correctamente.');
    }

    private function storeFileIfExists(Request $request, $profile, string $field, string $folder, array &$updates): void
    {
        if (!$request->hasFile($field)) {
            return;
        }

        $disk = 'public';
        $oldPath = $profile->{$field . '_path'};

        if ($oldPath && Storage::disk($disk)->exists($oldPath)) {
            Storage::disk($disk)->delete($oldPath);
        }

        $path = $request->file($field)->store("documentos/{$folder}", $disk);
        $updates[$field . '_path'] = $path;
    }
}
