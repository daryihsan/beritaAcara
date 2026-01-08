<aside class="w-72 min-h-screen bg-white text-gray-700 border-r border-gray-200 shadow-sm">

    <!-- LOGO -->
    <div class="h-20 flex items-center pl-25 px-6 text-4xl font-semibold tracking-wide text-blue-800 border-gray-200 bg-gradient-to-r from-blue-300 to-white">
        BAP
    </div>

    <nav class="px-4 py-6 space-y-2 text-base">

        <!-- DASHBOARD -->
        <a href="/dashboard"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 no-underline
           {{ request()->is('dashboard') && !request()->has('tahun') 
                ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold text-blue-700' 
                : 'text-gray-700 hover:!bg-blue-200 hover:!text-blue-900 hover:font-medium hover:no-underline' }}">
            <span class="glyphicon glyphicon-home text-xl"></span>
            <span class="text-xl">Dashboard</span>
        </a>

        <!-- BERITA ACARA -->
        <a href="/berita-acara/create"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 no-underline
           {{ request()->is('berita-acara/create') 
                ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold text-blue-700' 
                : 'text-gray-700 hover:!bg-blue-200 hover:!text-blue-900 hover:font-medium hover:no-underline' }}">
            <span class="glyphicon glyphicon-plus-sign text-xl"></span>
            <span class="text-xl">Berita Acara Baru</span>
        </a>

        <!-- SECTION -->
        <div class="mt-8 px-4 text-m font-semibold text-gray-500 uppercase tracking-widest">
            Arsip Pemeriksaan
        </div>

        @foreach([2026, 2025, 2024, 2023] as $year)
            <a href="{{ route('dashboard', ['tahun' => $year]) }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 no-underline
               {{ request('tahun') == $year 
                    ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold text-blue-700' 
                    : 'text-gray-600 hover:!bg-blue-200 hover:!text-blue-900 hover:font-medium hover:no-underline' }}">
                <span class="glyphicon glyphicon-folder-open text-xl"></span>
                <span class="text-xl">BAP Tahun {{ $year }}</span>
            </a>
        @endforeach

        @if(auth()->user()->isAdmin())
            <div class="mt-8 px-4 text-m font-semibold text-gray-500 uppercase tracking-widest">
                Panel Kontrol Admin
            </div>

            <a href="/admin/berita-acara"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 no-underline
               {{ request()->is('admin/*') 
                    ? 'bg-amber-50 border-l-4 border-amber-500 font-semibold text-amber-700' 
                    : 'text-gray-700 hover:!bg-amber-200 hover:!text-amber-900 hover:font-medium hover:no-underline' }}">
                <span class="glyphicon glyphicon-lock text-xl"></span>
                <span class="text-xl">Semua Berita Acara</span>
            </a>
        @endif

        <!-- LOGOUT -->
        <div class="pt-10">
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