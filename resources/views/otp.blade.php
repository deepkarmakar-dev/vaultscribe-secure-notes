<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OTP Verification</title>
<link rel="stylesheet" href="{{ asset('otpstyle.css') }}">
</head>

<body>

<div class="box">
    <h2>OTP Verification</h2>

    @if ($errors->has('otp'))
        <div class="msg">{{ $errors->first('otp') }}</div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <input
            type="text"
            name="otp"
            placeholder="Enter 6-digit OTP"
            maxlength="6"
            pattern="[0-9]{6}"
            autocomplete="one-time-code"
            required
        >

        <button type="submit">Verify OTP</button>
    </form>
</div>

</body>
</html>
