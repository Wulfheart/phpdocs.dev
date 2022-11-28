@props(['url', 'isActive', 'name'])


@php
    /** @var \App\ViewModels\Navigation\NamespaceInfo $namespace */
@endphp
<li>
    <a
        href="{{ $url }}" class="
    @if($isActive)
    block w-full pl-3.5 font-semibold text-sky-500
    @else
    block w-full pl-3.5 before:pointer-events-none text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300
    @endif
    ">
        {{ $name }}
    </a>
</li>
