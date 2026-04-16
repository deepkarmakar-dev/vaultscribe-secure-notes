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



     //  OTP PAGE
class OtpController extends Controller
{

    public function otp()
    {
        if (!session('pending_user')) {
            return redirect()->route('log')->withErrors(['error' => 'Please register first.']);
        }

        return view('otp');
    }

    public function verifyOtp(Request $req)
    {
        $req->validate([
            'otp' => 'required|digits:6',
        ]);
       
        if(session('registration_otp') != $req->otp) 
        {
           return back()->withErrors(['otp'=>'invalid otp']);   
        }  // $userData = [
    //     'name' => $request->name,
    //     'email' => $request->email,
    //     'password' => $pepperedPassword, // Original ya peppered password
    // ];
    // session(['pending_user' => $userData]);
 
    $userData = session('pending_user');



   
    // IMPROVEMENT: Track OTP attempts using session
    $attempts = session()->get('otp_attempts', 0);

    if ($attempts >= 5) {
        session()->forget(['otp_email', 'otp_attempts']);

        return redirect()->route('log')
            ->withErrors(['otp' => 'Too many OTP attempts. Please login again.']);
    }


    if ($userData) {
        $user = User::create([
            'name'              => $userData['name'],
            'email'             => $userData['email'],
            'password'          => $userData['password'], // Hash pehle hi ho chuka hai
            'email_verified_at' => now(),
        ]);


    }



    Auth::login($user);
    session()->forget(['pending_user', 'registration_otp', 'otp_attempts']);

    return redirect()->route('dashboard')->with('success', 'Registration successful!');


    return redirect()->route('log')->withErrors(['otp' => 'Something went wrong, please try again.']);
    }

}