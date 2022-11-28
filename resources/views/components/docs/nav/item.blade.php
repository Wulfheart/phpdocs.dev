@props(['url', 'name', 'active', 'children'])

<li class="relative">
    <a href="{{ $url }}" class="
    @if($active)
    block w-full pl-3.5 before:pointer-events-none before:absolute before:-left-1 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-y-1/2 before:rounded-full font-semibold text-sky-500 before:bg-sky-500
    @else
    block w-full pl-3.5 before:pointer-events-none before:absolute before:-left-1 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-y-1/2 before:rounded-full text-slate-500 before:hidden before:bg-slate-300 hover:text-slate-600 hover:before:block dark:text-slate-400 dark:before:bg-slate-700 dark:hover:text-slate-300
    @endif
    ">
        {{ $name }}
    </a>

    @if($active)
        <ul>
            @foreach($children as $child)
                <li class="ml-4">
                    <a href="{{ $child->url }}" class="block w-full pl-3.5 before:pointer-events-none before:absolute before:-left-1 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-y-1/2 before:rounded-full text-slate-500 before:hidden before:bg-slate-300 hover:text-slate-600 hover:before:block dark:text-slate-400 dark:before:bg-slate-700 dark:hover:text-slate-300">
                        {{ $child->name }}
                    </a>
                </li>

            @endforeach
        </ul>

    @endif
</li>
