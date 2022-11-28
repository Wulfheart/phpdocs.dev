@props(['namespace'])


@php
    /** @var \App\ViewModels\Navigation\NamespaceInfo $namespace */
@endphp
<li style="margin-left: {{ $namespace->depth * 14 }}px;">
    <a
        href="#" class="
    @if($namespace->isActive)
    block w-full pl-3.5 font-semibold text-sky-500
    @else
    block w-full pl-3.5 before:pointer-events-none text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300
    @endif
    ">
        {{ $namespace->name }}
    </a>

    @if($namespace->showChildren)
        <ul class="mt-2 space-y-2 lg:mt-4 lg:space-y-4 ">
            @foreach($namespace->children as $child)
                <x-docs.nav.namespace :namespace="$child"/>

            @endforeach
        </ul>

    @endif
</li>
