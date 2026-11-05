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
        <x-input-label for="quantity" value="Daudzums" />
        <x-text-input id="quantity" name="quantity" type="number" min="0" step="1" class="mt-1 block w-full" :value="old('quantity', $product->quantity)" required />
        <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
    </div>

    <div>
        <x-input-label for="description" value="Apraksts" />
        <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description', $product->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div>
        <x-input-label for="expiration_date" value="Derīguma termiņš" />
        <x-text-input id="expiration_date" name="expiration_date" type="date" class="mt-1 block w-full" :value="old('expiration_date', $product->expiration_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('expiration_date')" />
    </div>

    <div>
        <x-input-label for="status" value="Statuss" />
        <x-text-input id="status" name="status" type="text" class="mt-1 block w-full" :value="old('status', $product->status)" required />
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ $product->exists ? 'Saglabāt izmaiņas' : 'Izveidot produktu' }}</x-primary-button>
        <a class="text-sm text-gray-600 dark:text-gray-400 hover:underline" href="{{ route('products.index') }}">Atcelt</a>
    </div>
</form>
