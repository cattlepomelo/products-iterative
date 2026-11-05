<x-layout>
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
                    <x-session-success class="mb-4" />
                    <x-session-errors class="mb-4" />

                    <dl class="space-y-4">
                        <div>
                            <dt class="font-medium">Daudzums</dt>
                            <dd class="mt-1 flex items-center gap-3" data-quantity-control data-url="{{ route('products.quantity.update', $product) }}">
                                <button
                                    class="rounded-md border border-gray-600 px-3 py-1 text-lg hover:bg-gray-700 disabled:opacity-50"
                                    type="button"
                                    data-quantity-action="decrease"
                                    aria-label="Samazināt daudzumu"
                                >−</button>
                                <span class="min-w-8 text-center font-semibold" data-quantity-value aria-live="polite">{{ $product->quantity }}</span>
                                <button
                                    class="rounded-md border border-gray-600 px-3 py-1 text-lg hover:bg-gray-700 disabled:opacity-50"
                                    type="button"
                                    data-quantity-action="increase"
                                    aria-label="Palielināt daudzumu"
                                >+</button>
                                <label class="sr-only" for="quantity-amount">Maināmais daudzums</label>
                                <input id="quantity-amount" class="w-20 rounded-md border-gray-600 bg-gray-900 text-sm" type="number" min="1" max="1000000" value="1" data-quantity-amount>
                            </dd>
                            <p class="mt-2 text-sm text-gray-400" data-quantity-message role="status" aria-live="polite"></p>
                        </div>
                        <div>
                            <dt class="font-medium">Derīguma termiņš</dt>
                            <dd>{{ $product->expiration_date?->format('d.m.Y') ?? 'Nav norādīts.' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium">Statuss</dt>
                            <dd>{{ $product->status }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium">Apraksts</dt>
                            <dd class="whitespace-pre-line">{{ $product->description ?: 'Apraksts nav norādīts.' }}</dd>
                        </div>
                    </dl>

                    <form
                        class="mt-8 space-y-4"
                        method="POST"
                        action="{{ route('products.tags.update', $product) }}"
                        data-tag-editor
                        data-search-url="{{ route('tags.search') }}"
                    >
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="font-medium" for="tag-search">Produkta birkas</label>
                            <input
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-900 text-sm"
                                id="tag-search"
                                type="text"
                                autocomplete="off"
                                placeholder="Meklēt vai pievienot birku"
                                data-tag-input
                                aria-autocomplete="list"
                                aria-controls="tag-suggestions"
                            >
                            <ul class="mt-1 hidden rounded-md border border-gray-700 bg-gray-900" id="tag-suggestions" role="listbox" data-tag-suggestions></ul>
                            <p class="mt-1 text-sm text-gray-400" data-tag-feedback role="status" aria-live="polite"></p>
                        </div>

                        <div class="flex flex-wrap gap-2" data-tag-chips>
                            @foreach ($product->tags as $tag)
                                <span class="inline-flex items-center gap-2 rounded-full bg-indigo-500/20 px-3 py-1 text-sm text-indigo-100" data-tag-chip>
                                    <span>{{ $tag->name }}</span>
                                    <input type="hidden" name="tags[]" value="{{ $tag->name }}">
                                    <button class="font-bold hover:text-white" type="button" data-remove-tag aria-label="Noņemt birku {{ $tag->name }}">×</button>
                                </span>
                            @endforeach
                        </div>

                        <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500" type="submit">Atjaunināt birkas</button>
                    </form>

                    <a class="mt-6 inline-block text-sm text-gray-600 dark:text-gray-400 hover:underline" href="{{ route('products.index') }}">Atpakaļ uz produktu sarakstu</a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
