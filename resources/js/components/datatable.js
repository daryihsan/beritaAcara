const $ = window.jQuery;

export function initDatatable() {
    // Cek apakah tabel BAP ada
    if ($("#tableBap").length > 0) {
        if (window.showGlobalLoader) window.showGlobalLoader();
        $("#tableBap").DataTable({
            paging: true,
            ordering: true,
            info: true,
            pagingType: "full_numbers",
            columnDefs: [{ orderable: false, targets: [1, 5, 6] }],
            search: {
                smart: false,
            },
            dom: '<"dt-control-wrapper top"lf>rt<"dt-control-wrapper bottom"ip>',
            // Bahasa Indonesia & tombol
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
                            <h3 class="text-slate-800 font-bold text-base tracking-tight">Sedang Memproses</h3>
                            <p class="text-slate-500 text-lg font-medium animate-pulse">Mohon tunggu sebentar...</p>
                        </div>
                    </div>
                `,
            },

            processing: true,
            serverSide: true,
            autoWidth: false,
            // Request AJAX
            ajax: {
                url: window.location.origin + window.location.pathname,
                data: function (d) {
                    const urlParams = new URLSearchParams(
                        window.location.search,
                    );

                    d.tahun = urlParams.get("tahun");
                    d.filter_petugas = urlParams.get("filter_petugas");
                },
                // Request gagal
                error: function (xhr, error, thrown) {
                    console.error("DataTables Error:", error);

                    // Matikan processing indicator bawaan DataTables
                    $("#tableBap_processing").css("display", "none");

                    // Matikan global loader
                    if (window.hideGlobalLoader) window.hideGlobalLoader();

                    // Tampilkan pesan
                    alert("Gagal memuat data. Silakan refresh halaman.");
                },
            },

            // Definisi kolom & style
            columns: [
                // Kolom data
                {
                    data: "no_surat_tugas",
                    name: "no_surat_tugas",
                    className: "p-5 text-left",
                },
                {
                    data: "petugas_names",
                    name: "petugas.name",
                    className: "p-5 text-left",
                    orderable: false,
                },
                {
                    data: "objek_nama",
                    name: "objek_nama",
                    className: "p-5 text-left",
                },
                {
                    data: "tanggal_pemeriksaan",
                    name: "tanggal_pemeriksaan",
                    className: "p-5 text-left",
                },
                {
                    data: "tanggal_bap",
                    name: "created_at",
                    className: "p-5 text-left",
                },
                {
                    data: "status",
                    name: "status",
                    className: "p-5 text-center",
                    orderable: false,
                    searchable: false,
                },

                // Kolom aksi
                {
                    data: "action",
                    name: "action",
                    className: "p-5 text-center border-l border-slate-100",
                    searchable: false,
                    orderable: false,
                },
            ],
            initComplete: function () {
                var api = this.api();
                if (window.hideGlobalLoader) window.hideGlobalLoader();
                var searchInput = $(".dataTables_filter input");

                // Matikan event bawaan DataTables
                searchInput.off(".DT");

                // Variable timer
                var typingTimer;
                var doneTypingInterval = 1000;

                // Pasang event listener
                searchInput.on("keyup input", function () {
                    clearTimeout(typingTimer); // Reset timer tiap user ngetik huruf baru

                    var value = this.value; // Ambil teks yang diketik

                    // Hitung mundur
                    typingTimer = setTimeout(function () {
                        // Kalau user berhenti ngetik selama 1 detik, lanjut search
                        if (api.search() !== value) {
                            api.search(value).draw();
                        }
                    }, doneTypingInterval);
                });
            },
        });
    }

    if ($("#tableLog").length > 0) {
        if (window.showGlobalLoader) window.showGlobalLoader();
        $("#tableLog").DataTable({
            paging: true,
            ordering: false,
            info: true,
            pagingType: "full_numbers",
            search: { smart: false },
            dom: '<"dt-control-wrapper top"lf>rt<"dt-control-wrapper bottom"ip>',
            // Bahasa Indonesia & tombol
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya",
                },
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
                            <h3 class="text-slate-800 font-bold text-base tracking-tight">Sedang Memproses</h3>
                            <p class="text-slate-500 text-lg font-medium animate-pulse">Mohon tunggu sebentar...</p>
                        </div>
                    </div>
                `,
            },

            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: window.location.origin + window.location.pathname,
                data: function (d) {
                    d.start_date = $("#filter_start").val();
                    d.end_date = $("#filter_end").val();
                },
                error: function (xhr, error, thrown) {
                    console.error("Log Error:", error);
                    $("#tableLog_processing").css("display", "none");
                    if (window.hideGlobalLoader) window.hideGlobalLoader();
                },
            },
            columns: [
                {
                    data: "created_at",
                    name: "created_at",
                    className: "p-5 text-left",
                },
                {
                    data: "pelaku",
                    name: "causer.name",
                    className: "p-5 text-left",
                },
                { data: "event", name: "event", className: "p-5 text-left" },
                {
                    data: "description",
                    name: "description",
                    className: "p-5 text-left",
                },
                {
                    data: "detail",
                    name: "properties",
                    className: "p-5 text-left text-lg font-mono text-slate-600",
                },
            ],
            initComplete: function () {
                var api = this.api();
                if (window.hideGlobalLoader) window.hideGlobalLoader();
                var searchInput = $(".dataTables_filter input"); // Ambil elemen input search

                // Matikan event bawaan DataTables
                searchInput.off(".DT");

                // Variable timer
                var typingTimer;
                var doneTypingInterval = 1000;

                // Pasang event listener
                searchInput.on("keyup input", function () {
                    clearTimeout(typingTimer); // Reset timer tiap user ngetik huruf baru

                    var value = this.value; // Ambil teks yang diketik

                    // Hitung mundur
                    typingTimer = setTimeout(function () {
                        // Kalau user berhenti ngetik selama 1 detik, lanjut search
                        if (api.search() !== value) {
                            api.search(value).draw();
                        }
                    }, doneTypingInterval);
                });
            },
        });
        let isFiltered = false;

        $("#filter_start").on("change", function () {
            var minDate = $(this).val();
            $("#filter_end").attr("min", minDate);

            var endDate = $("#filter_end").val();
            if (endDate && endDate < minDate) {
                $("#filter_end").val(minDate);
            }
        });

        $("#btn-filter").on("click", function () {
            var start = $("#filter_start").val();
            var end = $("#filter_end").val();
            if (start && end && end < start) {
                alert("Tanggal 'Hingga' tidak boleh sebelum tanggal 'Dari'!");
                return;
            }
            isFiltered = true;
            $("#tableLog").DataTable().ajax.reload();
        });

        $("#btn-reset").on("click", function () {
            $("#filter_start").val("");
            $("#filter_end").val("").removeAttr("min");
            if (isFiltered) {
                $("#tableLog").DataTable().ajax.reload();
                isFiltered = false;
            }
        });
    }
}
