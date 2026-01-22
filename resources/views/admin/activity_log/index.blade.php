@extends('layouts.app')

@section('scripts')
    {{-- Copy script DataTables agar styling sama --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
@endsection

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="w-full md:w-auto text-left">
            <h1 class="text-5xl font-extrabold text-slate-800 tracking-tight">
                Riwayat Aktivitas
            </h1>
            <p class="text-slate-500 mt-2">
                Memantau detail perubahan data pada sistem.
            </p>
        </div>
        
        {{-- Area Tombol/Filter Kosong (Agar layout header tetap konsisten tingginya) --}}
        <div class="flex flex-wrap gap-2 items-center">
            {{-- Bisa ditambah filter tanggal log di sini nanti --}}
        </div>
    </div>

    {{-- Container Utama (Sama persis dengan BAP Index) --}}
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200">
        
        {{-- Spacer Top --}}
        <div class="dt-top"></div>

        <div class="table-scroll-x" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
            <table id="tableLog" class="w-full">
                <thead class="bg-slate-50">
                    <tr class="text-slate-700">
                        <th style="min-width: 150px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Waktu</th>
                        <th style="min-width: 200px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Pelaku (User)</th>
                        <th style="min-width: 100px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Aksi</th>
                        <th style="min-width: 250px;" class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Deskripsi</th>
                        <th style="min-width: 300px;" class="text-xl font-bold uppercase tracking-wider">Detail Perubahan</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                </tbody>
            </table>
        </div>
        
        {{-- Spacer Bottom --}}
        <div class="dt-bottom"></div>
    </div>
@endsection