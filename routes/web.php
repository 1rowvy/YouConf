<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuillUploadController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/auth/vk', function () {
    return Socialite::driver('vkontakte')->redirect();
})->name('auth.vk');
Route::get('vk/auth/callback', [LoginController::class, 'handleProviderCallback'])->name('auth.vk.callback');

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\SectionRegistrationController;

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    Route::get('/theses/create/{section_id}', [ThesisController::class, 'create'])->name('theses.create');
    Route::post('/theses/{section_id}/apply', [ThesisController::class, 'apply'])->name('theses.apply');

    Route::middleware('check.thesis.owner')->group(function () {
        Route::get('/theses/{id}', [ThesisController::class, 'show'])->name('theses.show');
        Route::get('/theses/{id}/edit', [ThesisController::class, 'edit'])->name('theses.edit');
        Route::post('/theses/{id}/update', [ThesisController::class, 'update'])->name('theses.update');
    });
    Route::delete('/theses/{thesis}/media/{media}', [ThesisController::class, 'deleteMedia']);
    Route::post('/chats/{chat}/messages', [ChatController::class, 'storeMessage']);

    Route::post('/theses/{thesis}/files', [FileController::class, 'store']);
    // //Route::delete('/theses/{thesis}/media/{media}', [FileController::class, 'destroy']);
});

Route::get('/', [StaticPageController::class, 'show'])
    ->defaults('slug', '');

Route::get('/api/pages', [StaticPageController::class, 'getAllPages']);

Route::get('/sections', [SectionController::class, 'index']);
Route::get('/sections/{section}', [SectionController::class, 'show']);
Route::get('/participants/public', [ParticipantController::class, 'publicIndex'])->name('participants.public');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/theses', [ThesisController::class, 'index'])->name('theses.index');
    Route::post('/theses/{id}/status', [ThesisController::class, 'updateStatus'])->name('theses.updateStatus');
    Route::post('/sections/{section}/register', [SectionRegistrationController::class, 'toggle'])->name('sections.register');
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
});

// routes/web.php

if (app()->environment('local')) {
    Route::get('/switch-user/{userId}', [UserController::class, 'switchUser'])->name('switch.user');
}


Route::post('/quill/upload', [QuillUploadController::class, 'upload'])
    ->name('moonshine.quill.upload');

use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\ExpertController;

Route::prefix('admin')->middleware(config('moonshine.middleware'))->group(function () {
    Route::middleware(config('moonshine.auth.middleware'))->group(function () {
        Route::get('/import-export/export-all', [ImportExportController::class, 'exportAll'])
            ->name('admin.export.all');
        Route::post('/import-export/import-all', [ImportExportController::class, 'importAll'])
            ->name('admin.import.all');
    });
});

Route::get('/{slug}', [StaticPageController::class, 'show'])
    ->name('static-pages.show');

//expert routes
Route::middleware(['auth', 'verified'])->prefix('expert')->group(function () {
    Route::get('/', [ExpertController::class, 'dashboard'])->name('dashboard');
});
