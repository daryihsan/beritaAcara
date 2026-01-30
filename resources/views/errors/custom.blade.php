@extends('layouts.app')

@section('content')
    <div class="min-h-[75vh] flex flex-col items-center justify-center p-6">

        <!-- Card container -->
        <div
            class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-500 hover:scale-[1.01]">

            <!-- Hiasan garis atas -->
            <div class="h-2 w-full bg-gradient-to-r from-red-500 via-orange-400 to-red-500"></div>

            <div class="p-10 text-center">

                <!-- Icon wrapper -->
                <div class="relative inline-block mb-8">
                    <div class="absolute inset-0 bg-red-100 rounded-full animate-ping opacity-75"></div>
                    <div
                        class="relative w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto shadow-inner border border-red-100">
                        <span class="glyphicon glyphicon-alert text-5xl drop-shadow-sm"></span>
                    </div>
                </div>

                <!-- Judul -->
                <h2 class="text-3xl font-black text-slate-800 mb-4 tracking-tight">
                    Terjadi Kesalahan
                </h2>

                <!-- Pesan error -->
                <p class="text-slate-500 mb-10 text-lg leading-relaxed font-medium px-4">
                    {{ $message ?? 'Sistem mengalami kendala saat memproses permintaan Anda. Silakan coba beberapa saat lagi.' }}
                </p>

                <!-- Tombol aksi -->
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <!-- Tombol kembali -->
                    <a href="{{ url()->previous() }}"
                        class="group flex items-center justify-center gap-3 px-6 py-3.5 bg-white border-2 border-slate-200 text-slate-600 font-bold rounded-xl hover:border-slate-300 hover:bg-slate-50 hover:text-slate-800 transition-all duration-200 no-underline shadow-sm hover:shadow-md">
                        <span
                            class="glyphicon glyphicon-arrow-left text-sm transition-transform group-hover:-translate-x-1"></span>
                        <span>Kembali</span>
                    </a>

                    <!-- Tombol dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center justify-center gap-3 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-blue-500/30 no-underline transform hover:-translate-y-0.5">
                        <span class="glyphicon glyphicon-home text-sm text-white"></span>
                        <span class="text-white">Ke Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Footer kecil -->
            <div class="bg-slate-50 py-3 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400 font-semibold tracking-wider uppercase m-0">System Notification</p>
            </div>
        </div>
    </div>
@endsection