<!-- <aside class="w-64 min-h-screen bg-white border-r">
    <div class="p-4 font-bold text-lg">
        ADIT'S
    </div>

    <nav class="px-4 space-y-2 text-sm">

        <a href="/dashboard" class="block px-3 py-2 rounded hover:bg-gray-100">
            Dashboard
        </a>

        <a href="/berita-acara/create" class="block px-3 py-2 rounded hover:bg-gray-100">
            Berita Acara Baru
        </a>

        <div class="mt-4 text-gray-500 text-xs uppercase">
            List Berita Acara
        </div>

        @foreach([2025, 2024, 2023] as $tahun)
            <a href="/berita-acara?tahun={{ $tahun }}"
               class="block px-3 py-2 rounded hover:bg-gray-100">
                {{ $tahun }}
            </a>
        @endforeach

        @if(auth()->user()->role === 'admin')
            <div class="mt-4 text-gray-500 text-xs uppercase">
                Admin
            </div>

            <a href="/admin/berita-acara"
               class="block px-3 py-2 rounded hover:bg-gray-100 text-red-600">
                Semua Berita Acara
            </a>
        @endif

        <form method="POST" action="/logout" class="mt-6">
            @csrf
            <button class="text-left w-full px-3 py-2 rounded hover:bg-gray-100">
                Logout
            </button>
        </form>

    </nav>
</aside> -->
<aside class="w-64 bg-white border-r">

    <div class="p-6 font-bold text-xl">
        BAP
    </div>

    <nav class="px-4 space-y-1 text-sm">

        <a href="/dashboard"
           class="block px-3 py-2 rounded hover:bg-gray-100">
            Dashboard
        </a>

        <a href="/berita-acara/create"
           class="block px-3 py-2 rounded hover:bg-gray-100">
            Berita Acara Baru
        </a>

        <div class="mt-4 text-xs text-gray-400 uppercase">
            Arsip
        </div>

        @foreach([2025, 2024, 2023] as $year)
            <a href="{{ route('dashboard', ['tahun' => $year]) }}"
               class="block px-3 py-2 rounded hover:bg-gray-100">
                {{ $year }}
            </a>
        @endforeach

        @if(auth()->user()->isAdmin())
            <div class="mt-4 text-xs text-gray-400 uppercase">
                Admin
            </div>

            <a href="/admin/berita-acara"
               class="block px-3 py-2 rounded hover:bg-gray-100 text-red-600">
                Semua Berita Acara
            </a>
        @endif

        <form method="POST" action="/logout" class="mt-6">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded hover:bg-gray-100 text-gray-600">
                Logout
            </button>
        </form>

    </nav>

</aside>
