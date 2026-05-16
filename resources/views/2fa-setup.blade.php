<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Setup 2FA</title>

    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>

<div class="auth-container">

    <div class="twofa-card">

        <h2>Enable Two-Factor Authentication</h2>

        <p class="auth-text">
            Scan the QR code using Google Authenticator
        </p>

        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="qr-section">

            <div class="qr-wrapper">
                {!! $QR_Image !!}
            </div>

            <div class="secret-box">
                <code>{{ $secret }}</code>
            </div>

        </div>

        <form method="POST" action="{{ route('2fa.enable') }}">
            @csrf

            <input
                type="text"
                name="one_time_password"
                maxlength="6"
                placeholder="Enter 6-digit OTP"
                required>

            <button type="submit">
                Enable 2FA
            </button>

        </form>

    </div>

</div>

</body>
</html>