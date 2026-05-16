<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'VaultScribe') }}</title>

    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>

@include('layouts.navigation')

<main>
    <div class="container">
        @yield('content')
    </div>
</main>

</body>
</html>