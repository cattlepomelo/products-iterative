<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Produktu katalogs') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-950 font-sans text-gray-100">
        <main class="flex min-h-screen items-center justify-center px-6 py-16">
            <section class="w-full max-w-3xl rounded-3xl border border-slate-700/70 bg-slate-900/80 p-8 shadow-2xl shadow-indigo-950/30 sm:p-12">
                <a class="inline-flex items-center gap-3 text-lg font-semibold text-white" href="{{ route('home') }}">
                    <x-application-logo class="h-11 w-11 text-indigo-300" />
                    <span>{{ config('app.name', 'Produktu katalogs') }}</span>
                </a>

                <p class="mt-12 text-sm font-semibold uppercase tracking-[0.2em] text-indigo-300">Vienkārša produktu pārvaldība</p>
                <h1 class="mt-4 max-w-2xl text-4xl font-bold tracking-tight text-white sm:text-5xl">
                    Tavs produktu katalogs vienuviet.
                </h1>
                <p class="mt-5 max-w-xl text-lg leading-8 text-slate-300">
                    Pārskatāmi apskati produktus, atjaunini cenas un uzturi kataloga informāciju.
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    @auth
                        <a class="rounded-xl bg-indigo-500 px-5 py-3 font-semibold text-white shadow-lg shadow-indigo-950/40 transition hover:bg-indigo-400" href="{{ route('products.index') }}">
                            Atvērt produktu katalogu
                        </a>
                    @else
                        <a class="rounded-xl bg-indigo-500 px-5 py-3 font-semibold text-white shadow-lg shadow-indigo-950/40 transition hover:bg-indigo-400" href="{{ route('login') }}">
                            Pieteikties
                        </a>
                        @if (Route::has('register'))
                            <a class="rounded-xl border border-slate-600 px-5 py-3 font-semibold text-slate-200 transition hover:border-slate-400 hover:bg-slate-800" href="{{ route('register') }}">
                                Izveidot kontu
                            </a>
                        @endif
                    @endauth
                </div>
            </section>
        </main>
    </body>
</html>
