<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use App\Models\ParticipantProfile;
use App\Notifications\ParticipantDisciplineStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantDisciplineReviewController extends Controller
{
    public function update(Request $request, ParticipantProfile $participant, Discipline $discipline): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,accepted,rejected'],
            'status_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $participant->disciplines()->where('discipline_id', $discipline->id)->firstOrFail();

        $participant->disciplines()->updateExistingPivot($discipline->id, [
            'status' => $data['status'],
            'status_notes' => $data['status_notes'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $participant->loadMissing('user');
        if ($participant->user) {
            $participant->user->notify(new ParticipantDisciplineStatusUpdated(
                $discipline,
                $data['status'],
                $data['status_notes'] ?? null
            ));
        }

        return redirect()
            ->route('admin.participants.show', $participant)
            ->with('discipline_success', 'Estado de la disciplina actualizado correctamente.');
    }
}
