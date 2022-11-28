@props(['name', 'content-infos'])

@php
    /** @var \App\ViewModels\Navigation\ContentInfo[] $contentInfos */
@endphp

@if(count($contentInfos) > 0)
    <x-docs.nav.section :name="$name">
        @foreach($contentInfos as $classLike)
            <x-docs.nav.item url="#" :isActive="$classLike->isActive" :name="$classLike->name"/>
        @endforeach
    </x-docs.nav.section>
@endif
