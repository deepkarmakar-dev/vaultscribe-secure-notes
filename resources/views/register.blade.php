<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

<div class="box">
<h2>Create Account</h2>

@if ($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <input name="name" placeholder="Full Name" value="{{ old('name') }}" required>
    <input name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>
    <input name="password" type="password" placeholder="Password" required>
    <input name="password_confirmation" type="password" placeholder="Confirm Password" required>

    <button>Register</button>
    <div class="footer-links">
           
            <a href="{{route('log')}}">Login</a>
        </div>
</form>
</div>

</body>
</html>
