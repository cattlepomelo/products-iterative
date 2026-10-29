<form method="POST" action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}" class="space-y-6">
    @csrf
    @if ($product->exists)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="name" value="Nosaukums" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="description" value="Apraksts" />
        <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description', $product->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div>
        <x-input-label for="price" value="Cena (€)" />
        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', $product->price)" required />
        <x-input-error class="mt-2" :messages="$errors->get('price')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ $product->exists ? 'Saglabāt izmaiņas' : 'Izveidot produktu' }}</x-primary-button>
        <a class="text-sm text-gray-600 dark:text-gray-400 hover:underline" href="{{ route('products.index') }}">Atcelt</a>
    </div>
</form>
