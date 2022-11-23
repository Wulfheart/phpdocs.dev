@props(['title', 'index'])
@php
    /** @var \ApiGen\Index\Index $index */
@endphp
<x-layout :title="$title">
    <x-nav></x-nav>
    <div>
        <x-docs.nav :index="$index"/>
    </div>
    {{ $slot }}
</x-layout>
