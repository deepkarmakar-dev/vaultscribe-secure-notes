<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use App\Models\ActivityLog;


class UserController extends Controller
{
   
     //  REGISTER
 

    public function register()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        // IMPROVEMENT: Password always hashed using Laravel Hash facade
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate OTP
        $otp = random_int(100000, 999999);

        // IMPROVEMENT: OTP stored as HASH (security best practice)
        $user->update([
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send OTP via email
        Mail::raw("Your OTP is: $otp (valid for 5 minutes), Please Do not share your otp to any one", function ($message) use ($user) {
            $message->to($user->email)->subject('OTP Verification');
        });

        // IMPROVEMENT: Email stored in session instead of URL (prevents tampering)
        session(['otp_email' => $user->email]);

        return redirect()->route('otp');
    }


     //  OTP PAGE
  

    public function otp()
    {
        if (!session('otp_email')) {
            return redirect()->route('log');
        }

        return view('otp');
    }

    public function verifyOtp(Request $req)
    {
    $req->validate([
        'otp' => 'required|digits:6',
    ]);

    $email = session('otp_email');
    if (!$email) {
        return redirect()->route('log');
    }

    // IMPROVEMENT: Track OTP attempts using session
    $attempts = session()->get('otp_attempts', 0);

    if ($attempts >= 5) {
        session()->forget(['otp_email', 'otp_attempts']);

        return redirect()->route('log')
            ->withErrors(['otp' => 'Too many OTP attempts. Please login again.']);
    }

    $user = User::where('email', $email)->first();

    if (!$user ||!$user->otp ||now()->greaterThan($user->otp_expires_at) ||!Hash::check(trim($req->otp), $user->otp)) {
        // IMPROVEMENT: Increase OTP attempt count
        session()->put('otp_attempts', $attempts + 1);

        return back()->withErrors([
            'otp' => 'Invalid or expired OTP'
        ]);
    }

    // OTP verified successfully
    $user->update([
        'otp' => null,
        'otp_expires_at' => null,
        'email_verified_at' => now(),
    ]);

    // IMPROVEMENT: Reset OTP session data after success
    session()->forget(['otp_email', 'otp_attempts']);

    return redirect()->route('log');
}

   
    //    LOGIN
   

    public function log()
    {
        return view('log');
    }
    
public function logstore(Request $req)
{
    $attempts = session()->get('login_attempts', 0);

    $req->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    //  After 3 failed attempts → Captcha required
    if ($attempts >= 3) {

        if (!$req->filled('g-recaptcha-response')) {
            return back()->withErrors([
                'email' => 'Captcha required'
            ]);
        }

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('NOCAPTCHA_SECRET'),
                'response' => $req->input('g-recaptcha-response'),
                'remoteip' => $req->ip(),
            ]
        );

        if (!$response->json('success')) {
            return back()->withErrors([
                'email' => 'Captcha failed'
            ]);
        }
    }

    $user = User::where('email', $req->email)->first();

    //  Invalid credentials
    if (!$user || !Hash::check($req->password, $user->password)) {

        session()->put('login_attempts', $attempts + 1);

        return back()->withErrors([
            'email' => 'Invalid credentials'
        ]);
    }

    //  Email not verified
    if (!$user->email_verified_at) {
        return back()->withErrors([
            'email' => 'Please verify email first'
        ]);
    }

    //  Reset failed attempts
    session()->forget('login_attempts');

    //  If 2FA Enabled
if ($user->google2fa_enabled) {

    Auth::login($user);

    // Regenerate FIRST
    $req->session()->regenerate();

    // Then set flag
    $req->session()->put('2fa_passed', false);

    return redirect()->route('2fa.challenge');
}

    //  Normal Login (No 2FA)
    Auth::login($user);
    $req->session()->regenerate();

    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'login',
        'ip_address' => $req->ip(),
        'user_agent' => $req->userAgent(),
    ]);

    return redirect()->route('dashboard');
}


    public function forget()
    {
        return view('forgetpassword');
    }

   public function forgetpass(Request $req)
{
    // 1️ Validation
    $req->validate([
        'email' => 'required|email'
    ]);

    //  Reset Link Send
    $status = Password::sendResetLink(
        $req->only('email')
    );

    // 3 Response Handle
    if ($status === Password::RESET_LINK_SENT) {
        return back()->with('status', 'Reset link sent to your email');
    } else {
        return back()->withErrors(['email' => 'Email not found']);
    }
}

public function showResetForm(Request $req, $token)
{
    return view('passwordreset', [
        'token' => $token,
        'email' => $req->email
    ]);
}
public function resetPassword(Request $request)
{

    //  Validation
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
        'token' => 'required'
    ]);

    
       if (!$request->filled('g-recaptcha-response')) {
            return back()->withErrors([
                'email' => 'Captcha required'
            ]);
        }

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('NOCAPTCHA_SECRET'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]
        );

        if (!$response->json('success')) {
            return back()->withErrors([
                'email' => 'Captcha failed'
            ]);
        }


    //  Password Reset Logic
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),

        function ($user, $password) {

            //  Password Update
            $user->update([
                'password' => Hash::make($password)
            ]);
        }
    );

    //  Response
    if ($status === Password::PASSWORD_RESET) {
        return redirect()->route('log')
            ->with('status', 'Password reset successful');
       
    }

    return back()->withErrors(['email' => 'Invalid or expired link']);
}

}



   
    
