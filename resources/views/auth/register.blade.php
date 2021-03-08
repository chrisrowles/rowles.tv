<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-yellow-600"/>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        @if(session('error_message'))
            <div role="alert" class="mb-4">
                <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                    Payment Failed
                </div>
                <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                    <p>{{ session('error_message') }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="signup-form">
        @csrf

        <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')"/>

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                         autofocus/>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')"/>

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required/>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')"/>

                <x-input id="password" class="block mt-1 w-full"
                         type="password"
                         name="password"
                         required autocomplete="new-password"/>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')"/>

                <x-input id="password_confirmation" class="block mt-1 w-full"
                         type="password"
                         name="password_confirmation" required/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-400 hover:text-indigo-300" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4" id="card-button">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
