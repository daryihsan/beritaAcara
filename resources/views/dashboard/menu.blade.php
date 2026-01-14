@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-7xl font-extrabold text-slate-800 tracking-tight">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <a href="/berita-acara/create" class="block no-underline transform transition hover:scale-105 hover:no-underline group">
            <div class="bg-blue-500 rounded-xl shadow-lg overflow-hidden border-b-8 border-blue-700">
                <div class="p-12 flex items-center justify-between text-white">
                    <span class="glyphicon glyphicon-file text-8xl opacity-80 group-hover:opacity-100 transition-opacity"></span>
                    <div class="text-right">
                        <div class="text-6xl font-bold italic">BARU</div>
                        <div class="text-2xl mt-2 tracking-widest opacity-90">BERITA ACARA</div>
                    </div>
                </div>
                <div class="bg-blue-600/50 py-3 text-center text-white text-sm font-bold tracking-tighter uppercase italic">
                    Buat Dokumen Sekarang
                </div>
            </div>
        </a>

        <a href="{{ route('dashboard', ['tahun' => date('Y')]) }}" class="block no-underline transform transition hover:scale-105 hover:no-underline group">
            <div class="bg-green-500 rounded-xl shadow-lg overflow-hidden border-b-8 border-green-700">
                <div class="p-12 flex items-center justify-between text-white">
                    <span class="glyphicon glyphicon-th-list text-8xl opacity-80 group-hover:opacity-100 transition-opacity"></span>
                    <div class="text-right">
                        <div class="text-6xl font-bold italic">LIST</div>
                        <div class="text-2xl mt-2 tracking-widest opacity-90">BERITA ACARA</div>
                    </div>
                </div>
                <div class="bg-green-600/50 py-3 text-center text-white text-sm font-bold tracking-tighter uppercase italic">
                    Lihat Seluruh Arsip
                </div>
            </div>
        </a>

    </div>
</div>

<style>
    /* Menghilangkan garis bawah default link pada elemen dashboard */
    .no-underline, .no-underline:hover {
        text-decoration: none !important;
    }
</style>
@endsection