<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TwoFactorController;
use App\Models\ActivityLog;

//  Public Routes

Route::get('/', function () {
    return view('log');
})->name('home');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('register', [UserController::class, 'store']);

Route::get('log', [UserController::class, 'log'])->name('log');
Route::post('log', [UserController::class, 'logstore'])
    ->middleware('throttle:5,1');


// Email OTP Verification


Route::get('otp', [UserController::class, 'otp'])->name('otp');
Route::post('otp', [UserController::class, 'verifyOtp'])->name('otp.verify');

//  Password Reset


Route::get('forget', [UserController::class, 'forget'])->name('forget');
Route::post('forgetpass', [UserController::class, 'forgetpass'])->name('password.email');
Route::get('/reset-password/{token}', [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.update');


//  2FA Routes (Only Auth Required)

Route::middleware('auth')->group(function () {

    Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');

    Route::get('/2fa/challenge', [TwoFactorController::class, 'challenge'])->name('2fa.challenge');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');

});

//  Protected Routes (Auth + 2FA Required)


Route::middleware(['auth', '2fa'])->group(function () {

    Route::get('/dashboard', [NoteController::class, 'dashboard'])->name('dashboard');

    Route::post('/notes', [NoteController::class, 'dashboardValue'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'notesedit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'notesupdate'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'notesdelete'])->name('notes.delete');

    Route::get('/notes/trash', [NoteController::class, 'showtrash'])->name('notes.trash');
    Route::patch('/notes/trash/restore-all', [NoteController::class, 'restoreAll'])->name('notes.restoreAll');
    Route::delete('/notes/trash/delete-all', [NoteController::class, 'forcedeleteall'])->name('notes.deleteAll');

    Route::patch('/notes/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('/notes/{id}/delete', [NoteController::class, 'forcedelete'])->name('notes.forceDelete');

    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])
    ->name('2fa.disable');
});

//  Logout


Route::post('logout', function (Request $req) {

    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'logout',
        'ip_address' => $req->ip(),
        'user_agent' => $req->userAgent(),
    ]);

    Auth::logout();

    session()->forget('login_attempts');

    $req->session()->invalidate();
    $req->session()->regenerateToken();

    return redirect()->route('log');

})->name('logout');