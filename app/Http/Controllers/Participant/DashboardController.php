<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $profile = $user->participantProfile()->with('disciplines')->first();

        return view('participant.dashboard', compact('user', 'profile'));
    }
}
