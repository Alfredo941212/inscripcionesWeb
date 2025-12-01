<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DisciplineController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $profile = Auth::user()->participantProfile()->with('disciplines')->first();

        if ($profile->status !== 'accepted') {
            return redirect()
                ->route('participant.dashboard')
                ->with('warning', 'Tu cuenta debe ser aceptada por el administrador antes de gestionar disciplinas.');
        }

        $selectedIds = $profile->disciplines->pluck('id')->all();

        $disciplines = Discipline::query()
            ->where('is_active', true)
            ->withCount('participantProfiles')
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(function (Discipline $discipline) use ($selectedIds) {
                $discipline->remaining_capacity = max($discipline->max_capacity - $discipline->participant_profiles_count, 0);
                $discipline->is_selected = in_array($discipline->id, $selectedIds, true);
                return $discipline;
            });

        return view('participant.disciplines.index', compact('profile', 'disciplines'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'discipline_id' => ['required', 'exists:disciplines,id'],
        ]);

        $profile = Auth::user()->participantProfile()->withCount('disciplines')->first();

        if ($profile->status !== 'accepted') {
            return redirect()
                ->route('participant.dashboard')
                ->with('warning', 'Tu cuenta debe ser aceptada por el administrador antes de gestionar disciplinas.');
        }

        if ($profile->disciplines_count >= 2) {
            return back()->withErrors(['discipline_id' => 'Solo puedes elegir hasta dos disciplinas.']);
        }

        $discipline = Discipline::findOrFail($request->input('discipline_id'));

        if (!$discipline->is_active) {
            return back()->withErrors(['discipline_id' => 'La disciplina seleccionada no esta disponible.']);
        }

        if ($profile->disciplines()->where('discipline_id', $discipline->id)->exists()) {
            return back()->withErrors(['discipline_id' => 'Ya seleccionaste esta disciplina.']);
        }

        $currentCount = $discipline->participantProfiles()->count();
        if ($currentCount >= $discipline->max_capacity) {
            return back()->withErrors(['discipline_id' => 'La disciplina ya alcanzo el cupo maximo.']);
        }

        DB::transaction(function () use ($profile, $discipline) {
            $profile->disciplines()->attach($discipline->id, [
                'selected_at' => now(),
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->route('participant.disciplines.index')
            ->with('success', 'Disciplina agregada correctamente.');
    }

    public function destroy(Request $request, Discipline $discipline): RedirectResponse
    {
        $profile = Auth::user()->participantProfile;

        if ($profile->status !== 'accepted') {
            return redirect()
                ->route('participant.dashboard')
                ->with('warning', 'Tu cuenta debe ser aceptada por el administrador antes de gestionar disciplinas.');
        }

        if (!$profile->disciplines()->where('discipline_id', $discipline->id)->exists()) {
            return back()->withErrors(['discipline_id' => 'No se encontro la disciplina seleccionada.']);
        }

        $profile->disciplines()->detach($discipline->id);

        return redirect()
            ->route('participant.disciplines.index')
            ->with('success', 'Disciplina removida correctamente.');
    }
}
