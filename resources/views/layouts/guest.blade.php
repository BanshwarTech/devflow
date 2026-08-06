<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>DevFlow</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-brand-light via-white to-white dark:from-brand-navy dark:via-gray-900 dark:to-brand-blue/20">
            <div class="pointer-events-none absolute -top-24 -left-24 h-72 w-72 rounded-full bg-brand-royal/30 dark:bg-brand-royal/25 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-brand-sky/30 dark:bg-brand-sky/20 blur-3xl"></div>

            <div class="relative mb-8 flex items-center gap-4">
                <img src="{{ asset('logo.png') }}" alt="DevFlow Logo" class="h-16 w-auto">
            </div>

            <div class="relative w-full max-w-md rounded-2xl bg-gradient-to-br from-brand-royal/50 via-brand-sky/30 to-transparent p-px shadow-2xl shadow-brand-royal/20 dark:shadow-black/40">
                <div class="rounded-2xl bg-gradient-to-br from-white to-brand-light/40 dark:from-gray-800 dark:to-brand-navy px-8 py-8">
                    <div class="mx-auto mb-6 h-1 w-14 rounded-full bg-gradient-to-r from-brand-royal to-brand-sky"></div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
