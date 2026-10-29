<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Produktu katalogs') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="holy-grail-layout min-h-screen bg-gray-100 dark:bg-gray-900">
            <header class="holy-grail-header bg-white dark:bg-gray-800">
                <a class="holy-grail-brand" href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    <span>{{ config('app.name', 'Produktu katalogs') }}</span>
                </a>

                @isset($header)
                    <div class="holy-grail-page-heading">
                        {{ $header }}
                    </div>
                @endisset
            </header>

            @include('layouts.navigation')

            <main class="holy-grail-main">
                {{ $slot }}
            </main>

            <aside class="holy-grail-aside bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300" aria-label="Papildu informācija">
                <h2 class="font-semibold text-lg text-slate-100">Informācija</h2>
                <p class="mt-2 text-sm">Izmanto navigāciju, lai pārvaldītu produktus un konta iestatījumus.</p>
            </aside>

            <footer class="holy-grail-footer text-sm text-gray-600 dark:text-gray-400">
                <p>&copy; {{ now()->year }} {{ config('app.name', 'Produktu katalogs') }}</p>
            </footer>
        </div>
    </body>
</html>
