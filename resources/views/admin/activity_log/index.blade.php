@extends('layouts.app')

@section('scripts')
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

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-end md:items-center gap-4 w-full md:w-auto">
            
            <div class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-56">
                    <input type="date" id="filter_start"
                        class="w-full border border-slate-300 text-slate-600 text-sm rounded-xl
                            focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-all">
                </div>

                <span class="text-slate-400 font-medium">sampai</span>

                <div class="relative w-full md:w-56">
                    <input type="date" id="filter_end"
                        class="w-full border border-slate-300 text-slate-600 text-sm rounded-xl
                            focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-all">
                </div>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button id="btn-filter" class="flex-1 md:flex-none bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all transform active:scale-95 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-white"></i>
                    <span class="text-white">Filter</span>
                </button>
                <button id="btn-reset" class="flex-1 md:flex-none bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 px-5 rounded-xl transition-all border border-slate-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset</span>
                </button>
            </div>
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
                        <th style="min-width: 150px;"
                            class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Waktu</th>
                        <th style="min-width: 200px;"
                            class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Pelaku (User)</th>
                        <th style="min-width: 100px;"
                            class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Aksi</th>
                        <th style="min-width: 250px;"
                            class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Deskripsi</th>
                        <th style="min-width: 300px;"
                            class="text-xl font-bold uppercase tracking-wider border-r border-slate-100">Detail Perubahan
                        </th>
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