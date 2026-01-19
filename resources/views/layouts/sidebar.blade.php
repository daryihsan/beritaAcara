{{-- OVERLAY (Latar gelap saat menu buka di HP) --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden backdrop-blur-sm transition-opacity cursor-pointer md:hidden"></div>

<button id="sidebar-float-toggle"
    class="fixed top-6 left-0 z-[60] 
           w-12 h-12 bg-white border border-gray-200 rounded-r-xl shadow-lg
           flex items-center justify-center
           transform -translate-x-full
           transition-transform duration-300 ease-in-out
           md:hidden">

    <span class="relative w-6 h-6 block">
        <span class="line line-1"></span>
        <span class="line line-2"></span>
        <span class="line line-3"></span>
    </span>
</button>

<aside id="sidebar-menu" 
       class="fixed inset-y-0 left-0 z-50 w-72 min-h-screen bg-white text-gray-700 border-r border-gray-200 shadow-2xl 
              transform -translate-x-full transition-transform duration-300 ease-in-out
              md:translate-x-0 md:static md:shadow-none">

    <div class="h-auto py-6 flex items-center justify-between px-6 mb-2">
        <div class="flex items-center justify-center w-full p-5 rounded-xl bg-gradient-to-br from-[#0b1a33] via-[#102a4e] to-[#020617] border border-blue-500/20 shadow-lg relative overflow-hidden group">
            {{-- Hiasan Glow Biru --}}
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-600/20 blur-[40px] rounded-full pointer-events-none"></div>
            {{-- Hiasan Glow Ungu --}}
            <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-indigo-600/20 blur-[40px] rounded-full pointer-events-none"></div>

            {{-- LOGO --}}
            <img src="{{ asset('assets/img/SIMBAP.png') }}" 
                 alt="Logo SIMBAP" 
                 class="relative z-10 w-36 h-auto object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.5)]">
        </div>
    </div>

    {{-- DIVIDER --}}
    <div class="px-6 mb-2">
        <div class="h-[1px] w-full bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
    </div>
    
    {{-- MENU NAVIGASI --}}
    <nav class="px-4 py-2 space-y-2 text-base overflow-y-auto h-[calc(100vh-150px)]">

        <a href="/dashboard"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 no-underline
           {{ request()->is('dashboard') && !request()->has('tahun') 
                ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold text-blue-700' 
                : 'text-gray-700 hover:!bg-blue-200 hover:!text-blue-900 hover:font-medium hover:no-underline' }}">
            <span class="glyphicon glyphicon-home text-xl"></span>
            <span class="text-xl">Dashboard</span>
        </a>

        <button id="berita-acara-toggle"
                class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 no-underline text-gray-700 hover:!bg-blue-200 hover:!text-blue-900 hover:font-medium hover:no-underline
                {{ request()->is('berita-acara*') || request()->has('tahun') ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold text-blue-700' : '' }}">
            <a class="flex items-center gap-3">
                <span class="glyphicon glyphicon-file text-xl"></span>
                <span class="text-xl">Berita Acara</span>
           </a>
            <span class="glyphicon glyphicon-chevron-down text-xl transition-transform duration-200" id="berita-acara-icon"></span>
        </button>

        <div id="berita-acara-submenu" class="overflow-hidden transition-all duration-300 ease-in-out max-h-0">
            <a href="/berita-acara/create" class="flex items-center gap-3 px-8 py-3 rounded-lg transition-all duration-200 no-underline {{ request()->is('berita-acara/create') ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold text-blue-700' : 'text-gray-700 hover:!bg-blue-200 hover:!text-blue-900 hover:font-medium hover:no-underline' }}">
                <span class="glyphicon glyphicon-plus-sign text-xl"></span>
                <span class="text-xl">Berita Acara Baru</span>
            </a>
            
            <div class="mt-4 px-8 text-sm font-semibold text-gray-500 uppercase tracking-widest">Arsip Pemeriksaan</div>
            @foreach([2026, 2025, 2024, 2023, 2022, 2021, 2020] as $year)
                <a href="{{ route('dashboard', ['tahun' => $year]) }}" class="flex items-center gap-3 px-8 py-2 rounded-lg transition-all duration-200 no-underline {{ request('tahun') == $year ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold text-blue-700' : 'text-gray-600 hover:!bg-blue-200 hover:!text-blue-900 hover:font-medium hover:no-underline' }}">
                    <span class="glyphicon glyphicon-folder-open text-xl"></span>
                    <span class="text-xl">BAP Tahun {{ $year }}</span>
                </a>
            @endforeach
        </div>

        @if(auth()->user()->isAdmin())
            <div class="mt-4 px-8 text-sm font-semibold text-gray-500 uppercase tracking-widest">Panel Kontrol Admin</div>
            <a href="/admin/berita-acara" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 no-underline {{ request()->is('admin/*') ? 'bg-amber-50 border-l-4 border-amber-500 font-semibold text-amber-700' : 'text-gray-700 hover:!bg-amber-200 hover:!text-amber-900 hover:font-medium hover:no-underline' }}">
                <span class="glyphicon glyphicon-lock text-xl"></span>
                <span class="text-xl">Semua Berita Acara</span>
            </a>
        @endif

        <div class="pt-10 pb-10">
            <form method="POST" action="/logout">
                @csrf
                <button class="flex items-center gap-3 w-full px-4 py-3 rounded-lg text-gray-600 hover:bg-red-200 hover:text-red-900 hover:font-medium transition-all duration-200">
                    <span class="glyphicon glyphicon-log-out text-xl"></span>
                    <span class="text-xl text-red-400 hover:text-red-900">Keluar Aplikasi</span>
                </button>
            </form>
        </div>

    </nav>
</aside>