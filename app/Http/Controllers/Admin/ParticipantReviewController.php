<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParticipantProfile;
use App\Notifications\ParticipantStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParticipantReviewController extends Controller
{
    public function show(ParticipantProfile $participant): View
    {
        $participant->load(['user', 'disciplines']);

        $reviewerIds = $participant->disciplines
            ->pluck('pivot.reviewed_by')
            ->filter()
            ->unique()
            ->values();

        $disciplineReviewers = $reviewerIds->isNotEmpty()
            ? \App\Models\User::whereIn('id', $reviewerIds)->pluck('name', 'id')
            : collect();

        return view('admin.participants.show', [
            'participant' => $participant,
            'disciplineReviewers' => $disciplineReviewers,
        ]);
    }

    public function updateStatus(Request $request, ParticipantProfile $participant): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,accepted,rejected'],
            'status_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $participant->update([
            'status' => $data['status'],
            'status_notes' => $data['status_notes'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $participant->loadMissing('user');
        if ($participant->user) {
            $participant->user->notify(new ParticipantStatusUpdated($participant));
        }

        return redirect()
            ->route('admin.participants.show', $participant)
            ->with('success', 'Estado actualizado correctamente.');
    }
}
