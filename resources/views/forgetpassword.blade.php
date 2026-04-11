/*<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

<div class="box">
<h2>Forgot Password</h2>

@if ($errors->any())
    <div class="error">{{ $errors->first() }}</div>
@endif

@if(session('status'))
    <div class="success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.forget.post') }}">
    @csrf

    <input type="email" name="email" placeholder="Enter your email" required>

    <button>Send Reset Link</button>
</form>

<br>

<div class="footer-links">
            <a href="{{ route('log') }}">Back to Login</a>
        </div>
</div>
</div>

</body>
</html>
