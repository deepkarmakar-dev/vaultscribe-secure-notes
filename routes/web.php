<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\NoteController;
use App\Models\ActivityLog;

//   PUBLIC ROUTES 


Route::get('/', fn() => view('log'))->name('home');

Route::get('log', [AuthController::class, 'log'])->name('log');
Route::post('log', [AuthController::class, 'logstore'])->middleware('throttle:5,1');

Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register', [RegisterController::class, 'store']);


// OTP PAGE 
Route::get('/otp', [OtpController::class, 'otp'])->name('otp.verify');

// OTP VERIFY 
Route::post('/otp', [OtpController::class, 'verifyOtp'])->name('otp.verify.post');


 // 2FA ROUTES 

Route::get('/2fa/challenge', [TwoFactorController::class, 'challenge'])->name('2fa.challenge');
Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');

 //    PASSWORD RESET


Route::get('forget', [PasswordController::class, 'forget'])->name('forget');
Route::post('forgetpass', [PasswordController::class, 'forgetpass'])->name('password.forget.post');

Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');

 // AUTH REQUIRED 

Route::middleware('auth')->group(function () {

    // 2FA setup 
    Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
});

 //  fully protected

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

     //  IMPORTANT 
Route::patch('/notes/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');

Route::delete('/notes/{id}/force-delete', [NoteController::class, 'forcedelete'])->name('notes.forceDelete');
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
