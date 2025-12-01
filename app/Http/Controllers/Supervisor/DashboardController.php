<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use App\Models\ParticipantProfile;
use App\Models\ReportExport;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'total' => ParticipantProfile::count(),
            'pending' => ParticipantProfile::where('status', 'pending')->count(),
            'accepted' => ParticipantProfile::where('status', 'accepted')->count(),
            'rejected' => ParticipantProfile::where('status', 'rejected')->count(),
        ];

        $disciplineStats = Discipline::withCount([
            'participantProfiles as total_participants',
            'participantProfiles as accepted_participants' => fn ($q) => $q->where('participant_profiles.status', 'accepted'),
        ])
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(function ($discipline) {
                $discipline->remaining_capacity = max($discipline->max_capacity - $discipline->accepted_participants, 0);
                return $discipline;
            });

        $statusTimeline = ParticipantProfile::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $reports = ReportExport::latest()->take(10)->get();

        return view('supervisor.dashboard', [
            'stats' => $stats,
            'disciplineStats' => $disciplineStats,
            'statusTimeline' => $statusTimeline,
            'reports' => $reports,
        ]);
    }
}
