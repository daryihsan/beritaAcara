<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <div class="flex min-h-screen">
        <div class="flex min-h-screen w-screen overflow-hidden">
            {{-- SIDEBAR --}}
            @include('layouts.sidebar')

            {{-- KONTEN --}}
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden"> {{-- NAVBAR --}}
                @include('layouts.navbar')

                {{-- PAGE CONTENT --}}
                <main class="flex-1 p-8 py-4 overflow-y-auto">
                    @yield('content')
                </main>

            </div>
        </div>
    </div>
    @stack('modals')

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    @stack('scripts')

    <div id="global-loader"
        class="fixed inset-0 z-[9999] bg-slate-900/30 backdrop-blur-[4px] hidden items-center justify-center transition-all duration-300 opacity-0"
        style="transition: opacity 0.3s ease-in-out;">
        <!-- Card Container -->
        <div class="bg-white/95 backdrop-blur-xl p-8 rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] flex flex-col items-center justify-center gap-5 border border-white/50 transform scale-95 transition-transform duration-300"
            id="loader-card">

            <!-- Custom Modern Spinner (Dual Ring) -->
            <div class="relative w-24 h-24">
                <!-- Ring Luar (Abu-abu tipis) -->
                <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                <!-- Ring Dalam (Biru Berputar) -->
                <div class="absolute inset-0 border-4 border-blue-600 rounded-full border-t-transparent animate-spin">
                </div>
                <!-- Icon Tengah -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="glyphicon glyphicon-hourglass text-blue-600/80 text-3xl animate-pulse"></span>
                </div>
            </div>

            <!-- Teks -->
            <div class="flex flex-col items-center gap-1">
                <h3 class="text-slate-800 font-bold text-base tracking-tight">Sedang Memproses</h3>
                <p class="text-slate-500 text-lg font-medium animate-pulse">Mohon tunggu sebentar...</p>
            </div>
        </div>
    </div>
</body>

</html>