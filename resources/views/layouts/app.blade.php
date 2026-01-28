<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        /* 1. Z-INDEX TERTINGGI (Agar tidak ketutup loader) */
        .swal2-container {
            z-index: 2000000000 !important; /* Angka gila biar aman */
        }

        /* 2. BODY POPUP LEBIH BESAR & MODERN */
        .swal2-popup {
            font-size: 1.4rem !important; /* Memperbesar ukuran text base */
            width: 25em !important;       /* Memperlebar kotak alert */
            padding: 1.8em !important;    /* Memberi ruang napas (whitespace) */
            border-radius: 20px !important; /* Sudut membulat modern (Rounded-3xl) */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; /* Bayangan lembut */
        }

        /* 3. JUDUL (TITLE) */
        .swal2-title {
            font-size: 2.0rem !important; /* Judul lebih besar */
            color: #1e293b !important;    /* Warna Slate-800 (Dark Grey) */
            font-weight: 700 !important;  /* Lebih tebal (Extra Bold) */
            margin-bottom: 0.5em !important;
        }

        /* 4. ISI PESAN (CONTENT) */
        .swal2-html-container {
            font-size: 1.2rem !important; /* Teks isi lebih terbaca */
            color: #64748b !important;    /* Warna Slate-500 (Soft Grey) */
            line-height: 1.2 !important;
        }

        /* 5. TOMBOL (BUTTONS) */
        .swal2-confirm, .swal2-cancel {
            padding: 12px 28px !important; /* Tombol lebih gendut */
            font-size: 1.0rem !important;
            border-radius: 12px !important; /* Tombol rounded */
            font-weight: 500 !important;
            text-transform: none !important; /* Jangan uppercase kaku */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            margin: 0 8px !important;      /* Jarak antar tombol */
            transition: all 0.2s ease !important;
        }

        /* Warna Tombol Custom (Opsional - biar match sama Tailwind) */
        .swal2-confirm {
            background-color: #2563eb !important; /* Blue-600 */
        }
        .swal2-confirm:hover {
            background-color: #1d4ed8 !important; /* Blue-700 */
            transform: translateY(-2px) !important; /* Efek tombol naik */
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4) !important; /* Bayangan makin tebal */
        }

        .swal2-cancel {
            background-color: #ef4444 !important; /* Red-500 */
        }
        .swal2-cancel:hover {
            background-color: #dc2626 !important; /* Red-600 */
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4) !important;
        }
        
        /* 6. IKON (ICONS) */
        .swal2-icon {
            width: 5em !important;        /* Ikon lebih besar */
            height: 5em !important;
            border-width: 0.25em !important; /* Garis ikon lebih tebal dikit */
            margin-bottom: 1.5em !important;
        }
    </style>
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

    <div id="global-loader"
        class="fixed inset-0 z-[999999] bg-slate-900/30 backdrop-blur-[4px] hidden items-center justify-center transition-all duration-300 opacity-0"
        style="z-index: 2147483647 !important; transition: opacity 0.3s ease-in-out;">
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
    @stack('modals')

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    @include('layouts.sweet-alert')

    <script>
        let isFormDirty = false;

        // 1. Deteksi Perubahan di Form (Ketik/Pilih)
        // Abaikan form search atau delete agar tidak false alarm
        $('form:not([id^="delete-form"]):not(#logout-form)').on('change input', function() {
            isFormDirty = true;
        });

        // 2. Jika Tombol Submit/Simpan ditekan, matikan alarm
        $('form').on('submit', function() {
            isFormDirty = false;
        });

        // 3. INTERCEPT KLIK LINK (Sidebar/Menu) - Masalah 3 & 4
        // 3. INTERCEPT KLIK LINK (Sidebar/Menu)
        $('a').on('click', function(e) {
            let targetUrl = $(this).attr('href');
            let toggleAttr = $(this).attr('data-toggle'); // Cek apakah ini Tab Bootstrap?

            // === PENGECUALIAN (JANGAN CEGAT INI) ===
            if (!targetUrl || targetUrl.startsWith('#') || targetUrl.startsWith('javascript')) return;
            if (toggleAttr === 'tab' || toggleAttr === 'pill') return;
            if ($(this).attr('onclick') && $(this).attr('onclick').includes('confirmLogout')) return;

            // === EKSEKUSI CEGAT ===
            // Jika Form Kotor (Ada perubahan belum save)
            if (isFormDirty) {
                e.preventDefault(); // Tahan dulu
                e.stopPropagation();

                Swal.fire({
                    title: 'Data Belum Disimpan!',
                    text: "Perubahan yang Anda buat akan hilang jika berpindah halaman.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Tetap Pindah',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        isFormDirty = false; // Reset status
                        window.location.href = targetUrl; // Lanjut pindah manual
                    } else {
                        let loader = document.getElementById('global-loader');
                        
                        if (loader) {
                            // Kembalikan ke state awal (Tersembunyi & Transparan)
                            loader.classList.add('opacity-0'); 
                            
                            // Tunggu animasi fade-out selesai (300ms), baru hidden
                            setTimeout(() => {
                                loader.classList.remove('flex');
                                loader.classList.add('hidden');
                                
                                // Reset card scale juga
                                let card = document.getElementById('loader-card');
                                if(card) card.classList.add('scale-95');
                            }, 300);
                        }
                        
                        // Jaga-jaga matikan loader template lain
                        $('.preloader').hide();
                        $('#loader').hide();
                        // User pilih BATAL (DIAM DI TEMPAT):
                        // === FIX UTAMA DISINI ===
                        // Kita cari semua kemungkinan ID loader dan paksa sembunyi
                        $('.loader').hide();         // Loader Class umum
                    }
                });
            }
        });

        // 4. HANDLING REFRESH / CLOSE TAB (Masalah 5)
        // Browser memaksakan alert bawaan di sini. Kita tidak bisa pakai SweetAlert.
        // Tapi kita bisa pastikan setidaknya alertnya muncul jika data belum disave.
        window.addEventListener('beforeunload', function (e) {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = ''; // Memicu alert bawaan browser
            }
        });
    </script>

    @stack('scripts')

    
</body>

</html>