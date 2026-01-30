<header class="h-24 bg-white border-b flex justify-between items-center px-6 sticky top-0 z-30">
    <div class="h-full flex text-l items-center font-medium gap-4">
        <button id="btn-open-sidebar"
            class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none transition-colors">
            <!-- Gambar garis tiga -->
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>

        <div class="h-full flex flex-col justify-center">
            <div class="text-l font-medium text-gray-800 leading-tight">
                {{ auth()->user()->name }}
                <span class="ml-1 text-m text-gray-400 tracking-wider">
                    ({{ auth()->user()->role }})
                </span>
            </div>
        </div>
    </div>
</header>