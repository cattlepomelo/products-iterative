@if (session('status'))
    <p {{ $attributes->merge(['class' => 'rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300']) }} role="status" aria-live="polite">
        {{ session('status') }}
    </p>
@endif
