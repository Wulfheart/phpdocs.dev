@props(['index'])

@php
    /** @var \App\ViewModels\Navigation $index */
@endphp

<nav class="text-base lg:text-sm w-64 pr-8 xl:w-72 xl:pr-16">
    <ul class="space-y-9">
        <x-docs.nav.section name="Namespaces">
            <x-docs.nav.namespace url="#" :namespace="$index->rootNamespace"/>
        </x-docs.nav.section>
        <x-docs.nav.content-info name="Classes" :content-infos="$index->current->classes"/>
        <x-docs.nav.content-info name="Interfaces" :content-infos="$index->current->interfaces"/>
        <x-docs.nav.content-info name="Traits" :content-infos="$index->current->traits"/>
        <x-docs.nav.content-info name="Enums" :content-infos="$index->current->enums"/>
        <x-docs.nav.content-info name="Exceptions" :content-infos="$index->current->exceptions"/>
        <x-docs.nav.content-info name="Functions" :content-infos="$index->current->functions"/>
    </ul>
</nav>
