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
                <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900 text-white shadow-lg">
                    <div class="relative aspect-video">
                        <img
                            class="h-full w-full object-cover"
                            src="https://media1.tenor.com/m/92fiqKsu_YoAAAAC/cs2-musor-drop.gif"
                            alt="Musordrop animēta reklāma"
                            loading="lazy"
                        >
                        <span class="absolute right-3 top-3 rounded bg-black/70 px-2 py-1 text-xs font-bold">
                            18+
                        </span>
                    </div>
                    <div class="p-4">
                        <span class="text-xs font-semibold uppercase tracking-widest text-indigo-300">Reklāma</span>
                        <h2 class="mt-1 font-semibold">Musordrop</h2>
                        <p class="mt-2 text-xs text-gray-300">18+ Azartspēles var izraisīt atkarību.</p>
                    </div>
                </div>
            </aside>

            <footer class="holy-grail-footer text-sm text-gray-600 dark:text-gray-400">
                <p>&copy; {{ now()->year }} {{ config('app.name', 'Produktu katalogs') }}</p>
            </footer>
        </div>
    </body>
</html>
