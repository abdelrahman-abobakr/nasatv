<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/Nasa_logo.png') }}">
    </head>
    <body class="font-sans text-gray-200 antialiased bg-dark selection:bg-primary selection:text-dark">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-gray-900 via-dark to-black">
            <div class="mb-8 transform hover:scale-105 transition-transform duration-500">
                <a href="/">
                    <x-application-logo class="h-24 w-auto drop-shadow-[0_0_15px_rgba(255,188,14,0.3)]" />
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-gray-900/50 backdrop-blur-xl border border-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
