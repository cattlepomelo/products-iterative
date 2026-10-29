<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $product->name }}</h2>
            <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                Rediģēt
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status'))
                        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
                    @endif

                    <dl class="space-y-4">
                        <div>
                            <dt class="font-medium">Cena</dt>
                            <dd>{{ number_format((float) $product->price, 2, ',', ' ') }} €</dd>
                        </div>
                        <div>
                            <dt class="font-medium">Apraksts</dt>
                            <dd class="whitespace-pre-line">{{ $product->description ?: 'Apraksts nav norādīts.' }}</dd>
                        </div>
                    </dl>

                    <a class="mt-6 inline-block text-sm text-gray-600 dark:text-gray-400 hover:underline" href="{{ route('products.index') }}">Atpakaļ uz produktu sarakstu</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
