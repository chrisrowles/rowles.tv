<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Open+Sans:wght@300;400;600;700;800&family=PT+Sans:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <script src="https://cdn.jwplayer.com/libraries/uyF4lkv3.js"></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/video.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased flex flex-col min-h-screen">
        <!-- Include navigation -->
        @include('layouts.navigation')
        <main class="pt-16 flex-grow" id="app-container">
            <!-- Yield main content -->
            @yield('content')
        </main>
        <!-- Include footer -->
{{--        @include('layouts.footer')--}}
        <!-- Yield footer (provides the ability to override the footer completely where needed) -->
{{--        @yield('footer')--}}
        <!-- Yield extra javascript -->
        @yield('scripts')
    </body>
</html>
