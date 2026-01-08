<header class="h-20 bg-white border-b flex justify-between px-6">
    <div class="h-full flex text-l items-center font-medium">
        {{ auth()->user()->name }}
        <span class="ml-1 text-m text-gray-400">
            ({{ auth()->user()->role }})
        </span>
    </div>
</header>
