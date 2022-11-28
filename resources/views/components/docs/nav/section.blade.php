@props(['name'])
<div>
    <h2 class="font-display font-medium text-slate-900 dark:text-white">{{ $name }}</h2>

    <ul class="mt-2 space-y-2 border-l-2 border-slate-200 lg:mt-4 lg:space-y-4 dark:border-slate-800">
        {{ $slot }}
    </ul>
</div>
