<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>2FA Verification</title>

    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>

<div class="auth-container">

    <div class="box">

        <h2>Two-Factor Authentication</h2>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <p class="auth-text">
            Enter the 6-digit code from Google Authenticator
        </p>

        <form method="POST" action="{{ route('2fa.verify') }}">
            @csrf

            <input
                type="text"
                name="one_time_password"
                maxlength="6"
                placeholder="000000"
                required
                autofocus>

            <button type="submit">
                Verify & Login
            </button>

        </form>

    </div>

</div>

</body>
</html>