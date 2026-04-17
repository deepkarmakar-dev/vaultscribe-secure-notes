<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class RegisterController extends Controller
{
    // show register page (GET /register)
    public function register()
    {
        return view('register');
    }

    // handle form 
    public function store(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // duplicate check
        if (User::where('email', $req->email)->exists()) {
            return back()->withErrors(['email' => 'User already exists']);
        }

        
       // generate OTP
$otp = rand(100000, 999999);

// ADD THIS (pepper)
$pepperedPassword = hash_hmac(
    'sha256',
    $req->password,
    config('app.pepper')
);

session([
    'pending_user' => [
        'name' => $req->name,
        'email' => $req->email,
        'password' => Hash::make($pepperedPassword), 
    ],
    'registration_otp' => Hash::make($otp),
    'otp_attempts' => 0,
    'otp_expires_at' => now()->addMinutes(5),
]);

        // send mail
        Mail::raw("Your OTP is: $otp", function ($message) use ($req) {
            $message->to($req->email)
                ->subject('Your OTP Code');
        });

        return redirect()->route('otp.verify')
            ->with('success', 'OTP sent to your email');
    }
}