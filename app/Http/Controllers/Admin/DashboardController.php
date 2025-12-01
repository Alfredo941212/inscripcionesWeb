<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use App\Models\ParticipantProfile;
use App\Models\ReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->only(['status', 'discipline', 'gender', 'search']);

        $participantsQuery = ParticipantProfile::query()
            ->with(['user', 'disciplines'])
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('worker_number', 'like', "%{$search}%");
                });
            })
            ->when($filters['discipline'] ?? null, function ($q, $disciplineId) {
                $q->whereHas('disciplines', fn ($disciplines) => $disciplines->where('disciplines.id', $disciplineId));
            })
            ->when($filters['gender'] ?? null, function ($q, $gender) {
                $q->whereHas('disciplines', fn ($disciplines) => $disciplines->where('gender', $gender));
            })
            ->orderByDesc('created_at');

        $participants = $participantsQuery->paginate(15)->withQueryString();

        $stats = [
            'total' => ParticipantProfile::count(),
            'pending' => ParticipantProfile::where('status', 'pending')->count(),
            'accepted' => ParticipantProfile::where('status', 'accepted')->count(),
            'rejected' => ParticipantProfile::where('status', 'rejected')->count(),
        ];

        $disciplines = Discipline::orderBy('name')->get();

        $reports = ReportExport::latest()->take(10)->get();

        $pendingDisciplineRequests = DB::table('participant_discipline as pd')
            ->join('participant_profiles as pp', 'pp.id', '=', 'pd.participant_profile_id')
            ->join('users as u', 'u.id', '=', 'pp.user_id')
            ->join('disciplines as d', 'd.id', '=', 'pd.discipline_id')
            ->select(
                'pd.id',
                'pd.status',
                'pd.status_notes',
                'pd.selected_at',
                'pd.created_at',
                'pp.id as participant_profile_id',
                'u.name as participant_name',
                'u.worker_number',
                'd.name as discipline_name',
                'd.category',
                'd.gender'
            )
            ->where('pd.status', 'pending')
            ->orderBy('pd.created_at')
            ->limit(10)
            ->get();

        $disciplineStatusSummary = DB::table('participant_discipline')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', [
            'participants' => $participants,
            'disciplines' => $disciplines,
            'filters' => $filters,
            'stats' => $stats,
            'reports' => $reports,
            'pendingDisciplineRequests' => $pendingDisciplineRequests,
            'disciplineStatusSummary' => $disciplineStatusSummary,
        ]);
    }
}
