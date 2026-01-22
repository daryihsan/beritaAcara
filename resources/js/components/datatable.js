const $ = window.jQuery;

export function initDatatable() {
    // Cek apakah tabel BAP ada (agar tidak error di halaman lain)
    if ($("#tableBap").length > 0) {
        if (window.showGlobalLoader) window.showGlobalLoader();
        $("#tableBap").DataTable({
            paging: true,
            ordering: true,
            info: true,
            // Mode Full Numbers untuk menampilkan tombol Awal/Akhir
            pagingType: "full_numbers",
            columnDefs: [{ orderable: false, targets: [1, 5] }],
            search: {
                smart: false,
            },
            dom: '<"dt-control-wrapper top"lf>rt<"dt-control-wrapper bottom"ip>',            
            // Bahasa Indonesia & Tombol
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya",
                },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                lengthMenu: "Tampilkan _MENU_ data",
                search: "Cari Data : ",
                zeroRecords: "Tidak ada data yang ditemukan",
                emptyTable: "Tidak ada data tersedia di tahun ini",

                processing: `
                    <div>
                        <div class="relative w-24 h-24">
                            <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-blue-600 rounded-full border-t-transparent animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="glyphicon glyphicon-hourglass text-blue-600/80 text-3xl animate-pulse"></span>
                            </div>
                        </div>
                        <div class="flex flex-col items-center gap-1 mt-2">
                            <h3 class="text-slate-800 font-bold text-base tracking-tight">Memproses Data</h3>
                            <p class="text-slate-500 text-lg font-medium animate-pulse">Mohon tunggu sebentar...</p>
                        </div>
                    </div>
                `
            },

            processing: true,
            serverSide: true, // WAJIB untuk 16rb data
            autoWidth: false, // Biar CSS Tailwind yang atur lebar
            // Request AJAX
            ajax: {
                url: window.location.href, 
                data: function (d) {
                    d.tahun = $('select[name="tahun"]').val();
                    d.filter_petugas = $('select[name="filter_petugas"]').val();
                },
                // ANTI NYANGKUT: Fungsi ini jalan kalau request GAGAL
                error: function (xhr, error, thrown) {
                    console.error("DataTables Error:", error);
                    
                    // 1. Matikan processing indicator bawaan DataTables
                    $("#tableBap_processing").css("display", "none");
                    
                    // 2. Matikan Global Loader (jika Anda pakai window.hideGlobalLoader)
                    if (window.hideGlobalLoader) window.hideGlobalLoader();

                    // 3. Tampilkan pesan user friendly (Opsional)
                    alert("Gagal memuat data. Silakan refresh halaman.");
                }
            },

            // Definisi Kolom & Style (Meniru <td class="p-5 text-left">)
            columns: [
                // Kolom Data (Pakai class 'p-5 text-left' agar pad uding sama kayak HTML asli)
                {data: 'no_surat_tugas', name: 'no_surat_tugas', className: 'p-5 text-left'},
                {data: 'petugas_names', name: 'petugas.name', className: 'p-5 text-left', orderable: false},
                {data: 'objek_nama', name: 'objek_nama', className: 'p-5 text-left'},
                {data: 'tanggal_pemeriksaan', name: 'tanggal_pemeriksaan', className: 'p-5 text-left'},
                {data: 'tanggal_bap', name: 'created_at', className: 'p-5 text-left'},
                
                // Kolom Aksi (Tengah & ada border kiri)
                {data: 'action', name: 'action', className: 'p-5 text-center border-l border-slate-100', searchable: false, orderable: false}
            ],
            initComplete: function () {
                var api = this.api();
                if (window.hideGlobalLoader) window.hideGlobalLoader();
                var searchInput = $('.dataTables_filter input'); // Ambil elemen input search

                // 1. Matikan event bawaan DataTables (biar gak langsung loading pas ngetik)
                searchInput.off('.DT') 
                
                // 2. Buat variable Timer
                var typingTimer;
                var doneTypingInterval = 1000; // Waktu tunggu 1 detik (1000ms)

                // 3. Pasang Event Listener Sendiri
                searchInput.on('keyup input', function () {
                    clearTimeout(typingTimer); // Reset timer tiap user ngetik huruf baru
                    
                    var value = this.value; // Ambil teks yang diketik
                    
                    // Mulai hitung mundur
                    typingTimer = setTimeout(function () {
                        // Kalau user berhenti ngetik selama 1 detik, BARU Search!
                        // Cek dulu apakah nilai berubah biar ga request dobel
                        if (api.search() !== value) {
                            api.search(value).draw();
                        }
                    }, doneTypingInterval);
                });
            }
        });
        // Event Filter
        $('select[name="tahun"], select[name="filter_petugas"]').on('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('tahun', $('select[name="tahun"]').val());
            if ($('select[name="filter_petugas"]').length) {
                url.searchParams.set('filter_petugas', $('select[name="filter_petugas"]').val());
            }
            window.history.pushState(null, '', url.toString());
            $("#tableBap").DataTable().draw();
        });
    }

    if ($("#tableLog").length > 0) {
        if (window.showGlobalLoader) window.showGlobalLoader();
        $("#tableLog").DataTable({
            paging: true,
            ordering: false, // Matikan sorting default agar urutan sesuai controller (Latest)
            info: true,
            pagingType: "full_numbers",
            search: { smart: false },
            
            // GUNAKAN STRUKTUR DOM YANG SAMA DENGAN BAP AGAR CSS-NYA SAMA
            dom: '<"dt-control-wrapper top"lf>rt<"dt-control-wrapper bottom"ip>',
            
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                paginate: { first: "Awal", last: "Akhir", next: "Selanjutnya", previous: "Sebelumnya" },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ aktivitas",
                infoEmpty: "Tidak ada aktivitas",
                lengthMenu: "Tampilkan _MENU_ aktivitas",
                search: "Cari Log : ",
                zeroRecords: "Tidak ada aktivitas yang ditemukan",
                emptyTable: "Belum ada riwayat aktivitas",

                processing: `
                    <div>
                        <div class="relative w-24 h-24">
                            <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-blue-600 rounded-full border-t-transparent animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="glyphicon glyphicon-hourglass text-blue-600/80 text-3xl animate-pulse"></span>
                            </div>
                        </div>
                        <div class="flex flex-col items-center gap-1 mt-2">
                            <h3 class="text-slate-800 font-bold text-base tracking-tight">Memproses Data</h3>
                            <p class="text-slate-500 text-lg font-medium animate-pulse">Mohon tunggu sebentar...</p>
                        </div>
                    </div>
                `
            },

            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: { 
                url: window.location.href,
                error: function (xhr, error, thrown) {
                    console.error("Log Error:", error);
                    $("#tableLog_processing").css("display", "none");
                    if (window.hideGlobalLoader) window.hideGlobalLoader();
                }
            },
            columns: [
                {data: 'created_at', name: 'created_at', className: 'p-5 text-left'},
                {data: 'pelaku', name: 'causer.name', className: 'p-5 text-left'},
                {data: 'event', name: 'event', className: 'p-5 text-left'},
                {data: 'description', name: 'description', className: 'p-5 text-left'},
                {data: 'detail', name: 'properties', className: 'p-5 text-left text-xs font-mono text-slate-600'}
            ],
            initComplete: function () {
                var api = this.api();
                if (window.hideGlobalLoader) window.hideGlobalLoader();
                var searchInput = $('.dataTables_filter input'); // Ambil elemen input search

                // 1. Matikan event bawaan DataTables (biar gak langsung loading pas ngetik)
                searchInput.off('.DT') 
                
                // 2. Buat variable Timer
                var typingTimer;
                var doneTypingInterval = 1000; // Waktu tunggu 1 detik (1000ms)

                // 3. Pasang Event Listener Sendiri
                searchInput.on('keyup input', function () {
                    clearTimeout(typingTimer); // Reset timer tiap user ngetik huruf baru
                    
                    var value = this.value; // Ambil teks yang diketik
                    
                    // Mulai hitung mundur
                    typingTimer = setTimeout(function () {
                        // Kalau user berhenti ngetik selama 1 detik, BARU Search!
                        // Cek dulu apakah nilai berubah biar ga request dobel
                        if (api.search() !== value) {
                            api.search(value).draw();
                        }
                    }, doneTypingInterval);
                });
            }
        });
    }
}
