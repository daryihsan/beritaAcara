const $ = window.jQuery;

export function initDatatable() {
    // Cek apakah tabel BAP ada (agar tidak error di halaman lain)
    if ($("#tableBap").length > 0) {
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
            },
        });
    }
}
