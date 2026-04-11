<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">


<title>{{ config('app.name', 'Notes App') }}</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="bg-gray-100 min-h-screen">


{{-- Navigation --}}
@include('layouts.navigation')

{{-- Page Content --}}
<main class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @yield('content')
    </div>
</main>


</body>
</html>