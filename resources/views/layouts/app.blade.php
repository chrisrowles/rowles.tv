<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Open+Sans:wght@300;400;600;700;800&family=PT+Sans:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/video.js') }}" defer></script>
        <script src="https://cdn.jwplayer.com/libraries/uyF4lkv3.js"></script>
    </head>
    <body class="font-sans antialiased flex flex-col min-h-screen">
        @include('layouts.navigation')
        <!-- Page Content -->
        <main class="pt-16 flex-grow" id="app-container">
            {{ $slot }}
        </main>
{{--        <footer class="py-10 text-gray-500">--}}
{{--            <div class="max-w-7xl mx-auto grid grid-cols-2 justify-between border-t border-gray-200">--}}
{{--                <div class="mt-5">--}}
{{--                    <h2 class="text-3xl mb-2">Contact Us</h2>--}}
{{--                    <p>Email: <a href="mailto:hello@danistream.local" class="text-yellow-300 hover:text-yellow-500">hello@danistream.local</a></p>--}}
{{--                    <p>Telephone: <a href="tel:+4401908 807222" class="text-yellow-300 hover:text-yellow-500">+44(0)1908 807 222</a></p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </footer>--}}
    </body>
</html>
