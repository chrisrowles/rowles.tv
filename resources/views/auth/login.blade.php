@extends('layouts.guest')
@section('content')
    <x-auth-card>
        <x-slot name="logo">
            <x-logo-text />
            <a href="/">
                <x-logo-image class="w-20 h-20 fill-current text-purple-600" />
            </a>
        </x-slot>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <x-label for="email" :value="__('Email')" />
                <x-input id="email" class="block mt-1 w-full form-input"
                         type="email"
                         name="email"
                         :value="old('email')"
                         required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />
                <x-input id="password" class="block mt-1 w-full form-input"
                         type="password"
                         name="password"
                         required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           name="remember">
                    <span class="ml-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4 gap-3">
                <a class="underline text-sm text-gray-400 hover:text-indigo-300" href="{{ route('register') }}">
                    {{ __('Register') }}
                </a>
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-400 hover:text-indigo-300"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button>
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
@endsection
