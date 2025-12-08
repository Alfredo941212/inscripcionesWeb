<?php

use App\Http\Controllers\OAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ParticipantReviewController;
use App\Http\Controllers\Admin\ParticipantDisciplineReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ParticipantDocumentController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\Participant\DashboardController as ParticipantDashboardController;
use App\Http\Controllers\Participant\DisciplineController;
use App\Http\Controllers\Participant\ProfileController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\ReportExportDownloadController;
use App\Http\Controllers\SecurityProtocolsController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\Admin\FtpsController;
use App\Http\Controllers\Admin\IpsecController;

use App\Models\Discipline;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $disciplines = Discipline::where('is_active', true)
        ->withCount('participantProfiles')
        ->orderBy('category')
        ->orderBy('name')
        ->get()
        ->map(function (Discipline $discipline) {
            $discipline->remaining_capacity = max($discipline->max_capacity - $discipline->participant_profiles_count, 0);
            return $discipline;
        });

    return view('home', compact('disciplines'));
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/files/participants/{participant}/{type}', ParticipantDocumentController::class)
        ->whereIn('type', ['constancia', 'cfdi', 'photo'])
        ->name('participants.documents.show');

    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [UserNotificationController::class, 'markAllRead'])->name('notifications.markRead');
});

Route::middleware(['auth', 'role:participant'])
    ->prefix('participant')
    ->name('participant.')
    ->group(function () {
        Route::get('dashboard', ParticipantDashboardController::class)->name('dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('disciplines', [DisciplineController::class, 'index'])->name('disciplines.index');
        Route::post('disciplines', [DisciplineController::class, 'store'])->name('disciplines.store');
        Route::delete('disciplines/{discipline}', [DisciplineController::class, 'destroy'])->name('disciplines.destroy');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('participants/{participant}', [ParticipantReviewController::class, 'show'])->name('participants.show');
        Route::put('participants/{participant}/status', [ParticipantReviewController::class, 'updateStatus'])->name('participants.status');
        Route::put('participants/{participant}/disciplines/{discipline}', [ParticipantDisciplineReviewController::class, 'update'])->name('participants.disciplines.update');
        Route::get('reports/{format}', ReportController::class)->name('reports.download');
        Route::get('reports/exports/{reportExport}', ReportExportDownloadController::class)->name('reports.exports.download');
    });

Route::middleware(['auth', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('dashboard', SupervisorDashboardController::class)->name('dashboard');
        Route::get('reports/exports/{reportExport}', ReportExportDownloadController::class)->name('reports.exports.download');
    });

    // Google OAuth
Route::get('/auth/google', [OAuthController::class, 'redirectGoogle'])->name('oauth.google');
Route::get('/auth/google/callback', [OAuthController::class, 'callbackGoogle']);

Route::get('/verificar-certs', function () {
    return [
        "private_exists" => Storage::exists('certs/private.pem'),
        "public_exists" => Storage::exists('certs/public.pem'),
    ];
});

Route::get('/sign', [DigitalSignatureController::class, 'sign']);
Route::get('/verify', [DigitalSignatureController::class, 'verify']);

Route::get('/admin/protocolos', [\App\Http\Controllers\Admin\SecurityProtocolsController::class, 'index'])
    ->name('admin.protocolos');

Route::get('/admin/verificar-firma', [\App\Http\Controllers\Admin\FirmaController::class, 'index'])
    ->name('admin.firma.index');

Route::post('/admin/verificar-firma', [\App\Http\Controllers\Admin\FirmaController::class, 'verificar'])
    ->name('admin.firma.verificar');

Route::post('/admin/ftps/send', [FtpsController::class, 'send'])
    ->name('admin.ftps.send');


//ISPEC
Route::get('/admin/ipsec', [IpsecController::class, 'index'])
    ->name('admin.ipsec');

Route::post('/admin/ipsec/send', [IpsecController::class, 'send'])
    ->name('admin.ipsec.send');
