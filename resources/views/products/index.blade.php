<x-layout>
    <x-slot name="header">
        <div class="products-page-heading">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-400">Katalogs</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-white sm:text-3xl">Produkti</h2>
                <p class="mt-2 text-sm text-gray-400">Pārvaldi sava kataloga produktus un to cenas.</p>
            </div>
            <a href="{{ route('products.create') }}" class="products-create-button inline-flex items-center justify-center px-5 py-3 bg-indigo-500 border border-indigo-400/30 rounded-xl font-semibold text-sm text-white shadow-lg shadow-indigo-950/40 hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 focus:ring-offset-gray-900 transition">
                <span aria-hidden="true" class="me-2 text-lg leading-none">+</span>
                Pievienot produktu
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="products-panel bg-gray-800/80 overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status'))
                        <p class="mb-5 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('status') }}</p>
                    @endif

                    @if ($products->isEmpty())
                        <p>Produktu vēl nav.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="products-table min-w-full divide-y divide-gray-700">
                                <thead class="text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Nosaukums</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Cena</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider">Darbības</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700/70">
                                    @foreach ($products as $product)
                                        <tr class="transition hover:bg-gray-700/30">
                                            <td class="px-4 py-4">
                                                <a class="font-semibold text-indigo-300 hover:text-indigo-200" href="{{ route('products.show', $product) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-4 font-medium text-gray-200">{{ number_format((float) $product->price, 2, ',', ' ') }} €</td>
                                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                                <a class="text-indigo-300 hover:text-indigo-200" href="{{ route('products.edit', $product) }}">Rediģēt</a>
                                                <form class="inline-block ms-3" method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Vai tiešām dzēst šo produktu?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:text-red-500" type="submit">Dzēst</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $products->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
