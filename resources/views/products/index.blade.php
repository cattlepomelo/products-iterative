<x-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Produkti</h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                Pievienot produktu
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="vite-css-test mb-4">Vite CSS ielādēts</p>

                    @if (session('status'))
                        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
                    @endif

                    @if ($products->isEmpty())
                        <p>Produktu vēl nav.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Nosaukums</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Cena</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider">Darbības</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="px-4 py-4">
                                                <a class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" href="{{ route('products.show', $product) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-4">{{ number_format((float) $product->price, 2, ',', ' ') }} €</td>
                                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                                <a class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" href="{{ route('products.edit', $product) }}">Rediģēt</a>
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
