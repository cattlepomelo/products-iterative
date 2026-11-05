@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300']) }} role="alert" aria-live="assertive">
        <p class="font-semibold">Darbību neizdevās izpildīt:</p>
        <ul class="mt-2 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
