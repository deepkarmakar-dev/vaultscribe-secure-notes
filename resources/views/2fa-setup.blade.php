@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">


<div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-12 w-full max-w-2xl">
    
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-50 rounded-2xl mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04"/>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-900">
            Two-Factor Authentication
        </h2>

        <p class="text-gray-500 mt-2">
            Scan the QR code and enter the 6-digit code below
        </p>
    </div>

    {{-- Error Message --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded-lg text-sm mb-6 text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-10 items-center">
        
        <!-- QR Section -->
        <div class="flex flex-col items-center">

            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200">
                <div class="bg-white p-3 rounded-xl shadow-sm">
                    {!! $QR_Image !!}
                </div>
            </div>
            
            <div class="mt-6 w-full">
                <span class="text-xs font-semibold text-gray-400 block mb-2 text-center">
                    Setup Key
                </span>

                <div class="bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-center">
                    <code class="text-xs font-mono text-indigo-600 font-semibold break-all">
                        {{ $secret }}
                    </code>
                </div>
            </div>

        </div>

        <!-- OTP Form -->
        <div>
            <form method="POST" action="{{ route('2fa.enable') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Verification Code
                    </label>

                    <input type="text"
                           name="one_time_password"
                           maxlength="6"
                           inputmode="numeric"
                           pattern="[0-9]*"
                           placeholder="000000"
                           required
                           class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-xl tracking-widest font-mono text-center focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                </div>

                <button type="submit"
                        class="w-full bg-gray-900 text-white font-semibold py-3 rounded-xl hover:bg-indigo-600 transition">
                    Enable Authentication
                </button>

            </form>
        </div>
        

    </div>
</div>


</div>
@endsection
