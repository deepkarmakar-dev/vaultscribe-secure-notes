<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<div class="box">
<h2>Login account</h2>

@if ($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{route('log')}}">
    @csrf

    <input name="email" type="email" placeholder="Email"  required>
    <input name="password" type="password" placeholder="Password" required>
 @if(session()->get('login_attempts',0) >= 3)
        <div class="g-recaptcha"
             data-sitekey="{{ config('services.recaptcha.site_key') }}">
        </div>
    @endif
    <button>Login</button>
    <div class="footer-links">
            <a href="{{route('forget')}}">Forgot Password?</a>
            <a href="{{route('register')}}">Register</a>
        </div>
</form>
</div>



</body>
</html>
