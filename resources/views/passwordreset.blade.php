<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<div class="box">
<h2>Reset Password</h2>

@if ($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif

@if(session('status'))
    <div class="success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <!-- Hidden Token -->
    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Hidden Email -->
    <input type="hidden" name="email" value="{{ $email }}">

    <!-- New Password -->
    <input type="password" name="password" placeholder="New Password" required>

    <!-- Confirm Password -->
    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
     
         <div class="g-recaptcha" 
             data-sitekey="{{ config('services.recaptcha.site_key') }}">
        </div>

    <button>Reset Password</button> 
    <div class="footer-links">
            <a href="{{ route('log') }}">Back to Login</a>
        </div>
</div>
</form>

<br>




</body>
</html>
