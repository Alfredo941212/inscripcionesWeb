<?php

namespace App\Http\Controllers;

use App\Models\ParticipantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParticipantDocumentController extends Controller
{
    public function __invoke(Request $request, ParticipantProfile $participant, string $type): StreamedResponse
    {
        $this->authorizeAccess($participant);

        $field = $this->resolveField($type);
        $path = $participant->{$field};

        abort_unless($path, 404, 'El documento solicitado no existe.');
        abort_unless(Storage::disk('public')->exists($path), 404, 'El archivo solicitado no se encuentra disponible.');

        return Storage::disk('public')->response($path);
    }

    private function authorizeAccess(ParticipantProfile $participant): void
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'supervisor') {
            return;
        }

        if ($user->role === 'participant' && $participant->user_id === $user->id) {
            return;
        }

        abort(403, 'No tienes permisos para ver este documento.');
    }

    private function resolveField(string $type): string
    {
        return match ($type) {
            'constancia' => 'constancia_path',
            'cfdi' => 'cfdi_path',
            'photo' => 'photo_path',
            default => abort(404, 'Documento no encontrado.'),
        };
    }
}
