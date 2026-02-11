@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
        <div class="w-full md:w-auto text-left">
            <h1 class="text-5xl font-extrabold text-slate-800 tracking-tight">Dashboard</h1>
            <p class="text-slate-500 mt-2">
                Selamat datang, <span class="font-bold text-blue-600">{{ $user->name }}</span>.
            </p>
        </div>

        <div class="flex flex-wrap gap-2 py-3 items-center w-full md:w-auto">
            @if($user->isAdmin())
                <form action="{{ route('dashboard') }}" method="GET" class="w-full md:w-auto" id="filter-form">
                    <select name="filter_petugas" onchange="this.form.submit()"
                        class="bg-white border border-slate-300 text-slate-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm cursor-pointer font-medium">
                        <option value="">-- Statistik Saya --</option>
                        @foreach($allPetugas as $p)
                            <option value="{{ $p->nip }}" {{ request('filter_petugas') == $p->nip ? 'selected' : '' }}>
                                {{ Str::limit($p->name, 25) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('berita-acara.create') }}"
            class="block no-underline transform transition hover:scale-[1.02] hover:shadow-xl hover:no-underline group duration-300">
            <div class="bg-blue-600 rounded-2xl shadow-lg overflow-hidden border-b-8 border-blue-800 relative">
                <div class="absolute -right-6 -bottom-6 text-blue-500/30 text-8xl">
                    <span class="glyphicon glyphicon-file rotate-12"></span>
                </div>

                <div class="p-6 md:p-8 flex items-center justify-between text-white relative z-10">
                    <span class="glyphicon glyphicon-plus-sign text-6xl md:text-7xl opacity-80 group-hover:opacity-100 transition-opacity drop-shadow-md"></span>
                    <div class="text-right">
                        <div class="text-4xl md:text-5xl font-black italic tracking-tighter">BARU</div>
                        <div class="text-sm md:text-lg mt-1 tracking-widest opacity-90 font-medium">BERITA ACARA</div>
                    </div>
                </div>
                <div class="bg-blue-800/40 py-2 md:py-3 text-center text-white text-xs font-bold tracking-widest uppercase backdrop-blur-sm">
                    Buat Dokumen Sekarang <span class="ml-2">→</span>
                </div>
            </div>
        </a>

        <a href="{{ route('dashboard', ['tahun' => date('Y')]) }}"
            class="block no-underline transform transition hover:scale-[1.02] hover:shadow-xl hover:no-underline group duration-300">
            <div class="bg-emerald-500 rounded-2xl shadow-lg overflow-hidden border-b-8 border-emerald-700 relative">
                <div class="absolute -right-6 -bottom-6 text-emerald-400/40 text-8xl">
                    <span class="glyphicon glyphicon-list-alt rotate-12"></span>
                </div>
                <div class="p-6 md:p-8 flex items-center justify-between text-white relative z-10">
                    <span class="glyphicon glyphicon-folder-open text-6xl md:text-7xl opacity-80 group-hover:opacity-100 transition-opacity drop-shadow-md"></span>
                    <div class="text-right">
                        <div class="text-4xl md:text-5xl font-black italic tracking-tighter">ARSIP</div>
                        <div class="text-sm md:text-lg mt-1 tracking-widest opacity-90 font-medium">LIHAT DATA</div>
                    </div>
                </div>
                <div class="bg-emerald-700/40 py-2 md:py-3 text-center text-white text-xs font-bold tracking-widest uppercase backdrop-blur-sm">
                    Lihat Seluruh Arsip <span class="ml-2">→</span>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-lg border border-slate-100 flex flex-col h-full overflow-hidden"
            id="chart-wrapper" data-stats="{{ json_encode([
                'labels' => $chartData['labels'],
                'personal' => $chartData['personal'],
                'global' => $chartData['global'],
                'targetName' => $targetName
            ]) }}">

            <div class="bg-slate-50/50 p-5 px-8 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-3">
                        <span class="glyphicon glyphicon-stats text-4xl text-blue-600"></span>
                        <h3 class="text-lg font-bold text-slate-800 m-0">Statistik Kinerja</h3>
                        <span class="hidden sm:inline text-slate-300">|</span>
                        <p class="text-base text-slate-500 font-medium m-0">Grafik Tren BAP (2020 - {{ date('Y') }})</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-white py-1 px-3 rounded-full border border-slate-200 shadow-sm">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pribadi</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="toggleGlobal" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Global</span>
                </div>
            </div>

            <div class="p-6 relative w-full bg-white flex-1 min-h-[240px]">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-white to-blue-50 p-6 rounded-3xl shadow-lg border border-blue-100 relative overflow-hidden group card-stat"
                data-stats-array="{{ json_encode($statsPersonal) }}" data-total-all="{{ $totalPersonalAllTime }}">

                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <span class="glyphicon glyphicon-user py-26 text-8xl text-blue-600"></span>
                </div>

                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-blue-600 text-lg font-bold uppercase tracking-wider mt-1 py-2">Total {{ $targetName }}</p>
                        <select
                            class="year-select bg-white/80 border border-blue-200 text-blue-800 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1.5 shadow-sm font-bold cursor-pointer outline-none hover:bg-white transition-colors">
                            <option value="all">Semua Tahun</option>
                            @foreach(range(date('Y'), 2020) as $y)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h2 class="text-5xl font-extrabold text-slate-800 mb-2 display-number">
                        {{ $statsPersonal[date('Y')] ?? 0 }}
                    </h2>

                    <span class="inline-block bg-blue-100 text-blue-700 text-[10px] px-2 py-1 rounded font-bold label-desc">Dokumen Tahun {{ date('Y') }}</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-emerald-50 p-6 rounded-3xl shadow-lg border border-blue-100 relative overflow-hidden group card-stat"
                data-stats-array="{{ json_encode($statsGlobal) }}" data-total-all="{{ $totalGlobalAllTime }}">

                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-10 transition-opacity">
                    <span class="glyphicon glyphicon-globe py-28 text-8xl text-emerald-900"></span>
                </div>

                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-slate-500 text-lg font-bold uppercase tracking-wider mt-1 py-2">Total dari Seluruh Pegawai</p>
                        <select
                            class="year-select bg-slate-50 border border-slate-200 text-slate-600 text-xs rounded-lg focus:ring-slate-500 focus:border-slate-500 block p-1.5 shadow-sm font-bold cursor-pointer outline-none hover:bg-white transition-colors">
                            <option value="all">Semua Tahun</option>
                            @foreach(range(date('Y'), 2020) as $y)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-baseline gap-2">
                        <h2 class="text-4xl font-extrabold text-slate-700 display-number">
                            {{ $statsGlobal[date('Y')] ?? 0 }}
                        </h2>
                        <span class="text-xs text-slate-400 font-medium">BAP</span>
                    </div>

                    <span class="inline-block bg-emerald-100 text-emerald-600 text-[10px] px-2 py-1 rounded font-bold label-desc">Data Tahun {{ date('Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .no-underline,
        .no-underline:hover {
            text-decoration: none !important;
        }
    </style>
@endsection