@props(['active'])

@php
$classes = ($active ?? false)
            ? 'holy-grail-nav-link flex items-center rounded-lg border-l-4 border-indigo-400 bg-indigo-500/15 px-3 py-3 text-sm font-semibold text-indigo-100 transition duration-150 ease-in-out'
            : 'holy-grail-nav-link flex items-center rounded-lg border-l-4 border-transparent px-3 py-3 text-sm font-medium text-slate-300 transition duration-150 ease-in-out hover:border-slate-500 hover:bg-slate-800 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes])->merge(($active ?? false) ? ['aria-current' => 'page'] : []) }}>
    {{ $slot }}
</a>
