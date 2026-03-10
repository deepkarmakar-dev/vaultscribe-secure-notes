@extends('layouts.app')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center">


<div class="w-full max-w-md bg-white shadow rounded-lg">

    <div class="border-b px-6 py-3 text-center font-semibold">
        Two-Factor Authentication
    </div>

    <div class="p-6">

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <p class="text-center text-gray-600 mb-4">
            Enter the 6-digit OTP from your Authenticator App
        </p>

        <form method="POST" action="{{ route('2fa.verify') }}">
            @csrf

            <div class="mb-4">
                <input
                    type="text"
                    name="one_time_password"
                    maxlength="6"
                    placeholder="Enter OTP"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                    autofocus>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Verify & Login
            </button>

        </form>

    </div>

</div>


</div>

@endsection
