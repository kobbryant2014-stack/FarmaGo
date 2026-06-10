<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FarmaGo') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-sky-50">
            @if (request()->routeIs('login'))
                {{ $slot }}
            @else
                <div class="flex min-h-screen flex-col items-center justify-center px-6 py-10">
                    <a href="/" class="mb-8 inline-flex items-center justify-center" aria-label="Ir al inicio de FarmaGo">
                        <x-application-logo class="h-24 w-56" />
                    </a>

                    <div class="w-full max-w-md rounded-2xl border border-sky-100 bg-white/95 p-6 shadow-2xl shadow-sky-900/10">
                        {{ $slot }}
                    </div>
                </div>
            @endif
        </div>

        @stack('scripts')
    </body>
</html>
