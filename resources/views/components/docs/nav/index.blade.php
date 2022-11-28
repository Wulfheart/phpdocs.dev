@props(['index'])

@php
    /** @var \App\ViewModels\Navigation $index */
@endphp

<nav class="text-base lg:text-sm w-64 pr-8 xl:w-72 xl:pr-16">
    <ul class="space-y-9">
        <x-docs.nav.section name="Namespaces">
            <x-docs.nav.item url="#" :name="$index->rootNamespace->name" :active="false" :children="$index->rootNamespace->children"/>
        </x-docs.nav.section>

    </ul>
</nav>
