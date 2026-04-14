<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\registerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\passwordController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\NoteController;
use App\Models\ActivityLog;

// /  PUBLIC ROUTES (Guest only)


Route::get('/', fn() => view('log'))->name('home');

Route::get('log', [AuthController::class, 'log'])->name('log');
Route::post('log', [AuthController::class, 'logstore'])->middleware('throttle:5,1');

Route::get('register', [registerController::class, 'register'])->name('register');
Route::post('register', [registerController::class, 'store']);

 // 2FA ROUTES (IMPORTANT — NO auth middleware)

Route::get('/2fa/challenge', [TwoFactorController::class, 'challenge'])->name('2fa.challenge');
Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');

 // PASSWORD RESET


Route::get('forget', [passwordController::class, 'forget'])->name('forget');
Route::post('forgetpass', [passwordController::class, 'forgetpass'])->name('password.forget.post');

Route::get('/reset-password/{token}', [passwordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [passwordController::class, 'resetPassword'])->name('password.update');

 // AUTH REQUIRED (LOGIN HO CHUKA HAI)

Route::middleware('auth')->group(function () {

    // 2FA setup (first time enable)
    Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
});

 // FULLY PROTECTED (AUTH + 2FA)


Route::middleware(['auth', '2fa'])->group(function () {

    Route::get('/dashboard', [NoteController::class, 'dashboard'])->name('dashboard');

    // Notes
    Route::post('/notes', [NoteController::class, 'dashboardValue'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'notesedit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'notesupdate'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'notesdelete'])->name('notes.delete');

    // Trash
    Route::get('/notes/trash', [NoteController::class, 'showtrash'])->name('notes.trash');
    Route::patch('/notes/trash/restore-all', [NoteController::class, 'restoreAll'])->name('notes.restoreAll');
    Route::delete('/notes/trash/delete-all', [NoteController::class, 'forcedeleteall'])->name('notes.deleteAll');
    Route::patch('/notes/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('/notes/{id}/delete', [NoteController::class, 'forcedelete'])->name('notes.forceDelete');

    // Disable 2FA
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');
});

 // LOGOUT


Route::post('logout', function (Request $req) {

    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'logout',
        'ip_address' => $req->ip(),
        'user_agent' => $req->userAgent(),
    ]);

    Auth::logout();

    session()->forget(['2fa_passed', 'login_attempts', '2fa_user_id']);
    $req->session()->invalidate();
    $req->session()->regenerateToken();

    return redirect()->route('log');

})->name('logout');
