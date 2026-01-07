<header class="h-14 bg-white border-b flex items-center justify-between px-6">

    <div class="font-medium">
        {{ auth()->user()->name }}
        <span class="text-xs text-gray-400">
            ({{ auth()->user()->role }})
        </span>
    </div>

    <form method="POST" action="/logout">
        @csrf
        <button class="text-sm text-red-600 hover:underline">
            Logout
        </button>
    </form>

</header>