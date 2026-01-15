import "./bootstrap";

// ==========================================
// 1. LOGIKA VALIDASI FORM & TAB (jQuery)
// ==========================================

function validateTab(tabId) {
    let isValid = true;
    let tabPane = $(tabId);
    let tabLink = $('.nav-tabs a[href="' + tabId + '"]').parent();

    tabPane
        .find("input[required], textarea[required], select[required]")
        .each(function () {
            if ($(this).val() === null || $(this).val().trim() === "") {
                isValid = false;
                $(this).css("border-color", "#fb7185"); // Merah
            } else {
                $(this).css("border-color", "#e2e8f0"); // Reset
            }
        });

    if (isValid) {
        tabLink.removeClass("tab-error").addClass("tab-success");
    } else {
        tabLink.removeClass("tab-success").addClass("tab-error");
    }

    return isValid;
}

$(document).ready(function () {
    // Tombol Next Tab
    $(document).on("click", ".btn-next", function () {
        var currentTabId = "#" + $(this).closest(".tab-pane").attr("id");

        if (validateTab(currentTabId)) {
            $("#formAlert").hide();
            var nextTab = $(this).data("next");
            $('.nav-tabs a[href="' + nextTab + '"]').tab("show");
        } else {
            $("#formAlert").fadeIn();
            setTimeout(function () {
                $("#formAlert").fadeOut();
            }, 3000);

            // Fokus ke error pertama
            $(currentTabId)
                .find("input[required], textarea[required]")
                .filter(function () {
                    return !$(this).val();
                })
                .first()
                .focus();
        }
    });

    // Validasi saat Submit Form
    $("#formBeritaAcara").on("submit", function (e) {
        let formIsValid = true;
        let firstErrorTab = null;
        let isAnyFilled = false;

        // Cek apakah ada isian sama sekali (untuk logika 'form changed')
        $(this)
            .find("input[required], textarea[required]")
            .each(function () {
                if ($(this).val().trim() !== "") {
                    isAnyFilled = true;
                    return false;
                }
            });

        // Validasi tiap tab
        $(".tab-pane").each(function () {
            let id = "#" + $(this).attr("id");
            if (!validateTab(id)) {
                formIsValid = false;
                if (!firstErrorTab) firstErrorTab = id;
            }
        });

        if (!formIsValid) {
            e.preventDefault();
            $("#formAlertGlobal").fadeIn();
            $('.nav-tabs a[href="' + firstErrorTab + '"]').tab("show");
            return false;
        }

        // Jika valid, matikan proteksi unload agar bisa submit
        window.onbeforeunload = null;
    });

    // Auto-fill Petugas
    $(document).on("input", ".input-nama", function () {
        var val = $(this).val();
        var row = $(this).closest(".petugas-row");
        var option = $("#list-petugas option").filter(function () {
            return $(this).val() === val;
        });

        if (option.length) {
            row.find(".input-nip").val(option.data("nip"));
            row.find(".input-pangkat").val(option.data("pangkat"));
            row.find(".input-jabatan").val(option.data("jabatan"));
        } else {
            row.find(".input-nip, .input-pangkat, .input-jabatan").val("");
        }
    });

    // Tambah Baris Petugas
    $("#btn-tambah-petugas").click(function () {
        var newRow = $("#row-0").clone();
        newRow.find("input").val("");
        newRow.find(".btn-hapus").removeAttr("disabled");
        $("#petugas-container").append(newRow);
    });

    // Hapus Baris Petugas
    $(document).on("click", ".btn-hapus", function () {
        $(this).closest(".petugas-row").remove();
    });
});

// ==========================================
// 2. PROTEKSI DATA HILANG (UNSAVED CHANGES)
// ==========================================

var formChanged = false;

$(document).on(
    "input change",
    "#formBeritaAcara input, #formBeritaAcara textarea, #formBeritaAcara select",
    function () {
        formChanged = true;
    }
);

window.onbeforeunload = function (e) {
    if (formChanged) {
        e.preventDefault();
        return "Data belum disimpan!";
    }
};

$('form[action="/logout"]').on("submit", function (e) {
    let pesan = formChanged
        ? "⚠️ PERINGATAN: Data yang sudah diketik akan hilang.\n\nYakin ingin keluar aplikasi?"
        : "Apakah Anda yakin ingin keluar dari aplikasi?";

    if (!confirm(pesan)) {
        e.preventDefault();
    } else {
        window.onbeforeunload = null;
    }
});

// ==========================================
// 3. LOGIKA SIDEBAR
// ==========================================

document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("berita-acara-toggle");

    // Cek keberadaan tombol agar tidak error di halaman Login
    if (toggleButton) {
        const submenuId = "berita-acara-submenu";
        const iconId = "berita-acara-icon";
        const storageKey = "berita-acara-open";

        const submenu = document.getElementById(submenuId);
        const icon = document.getElementById(iconId);

        // --- PERBAIKAN DI SINI ---
        // Kita ganti PHP {{ request()->... }} dengan Javascript window.location
        const currentPath = window.location.pathname;
        const currentSearch = window.location.search;

        // Cek apakah URL mengandung 'berita-acara' ATAU ada parameter '?tahun='
        const isSubActive =
            currentPath.includes("berita-acara") ||
            currentSearch.includes("tahun");

        const storedState = localStorage.getItem(storageKey);

        // Buka jika URL aktif ATAU user terakhir kali membiarkannya terbuka
        const shouldBeOpen = isSubActive || storedState === "true";

        if (shouldBeOpen) {
            submenu.style.transition = "none";
            submenu.style.maxHeight = submenu.scrollHeight + "px";
            icon.classList.add("rotate-180");

            setTimeout(() => {
                submenu.style.transition = "max-height 0.3s ease-in-out";
            }, 50);
        }

        toggleButton.addEventListener("click", function (e) {
            const isOpen =
                submenu.style.maxHeight !== "0px" &&
                submenu.style.maxHeight !== "";

            if (isOpen) {
                submenu.style.maxHeight = "0px";
                icon.classList.remove("rotate-180");
                localStorage.setItem(storageKey, "false");
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
                icon.classList.add("rotate-180");
                localStorage.setItem(storageKey, "true");
            }
        });
    }
});
// ==========================================
// 4. LOGIKA TANDA TANGAN DIGITAL (TTD)
// ==========================================

let activeRow = null;
let signaturePad = null;
const canvas = document.getElementById("signature-pad");

// Fungsi Resize Canvas
function resizeCanvas() {
    // 1. FIX GEPENG: Jika tinggi terbaca 0 (karena modal belum full load), paksa tinggi default 200px
    const width = canvas.offsetWidth > 0 ? canvas.offsetWidth : 500;
    const height = canvas.offsetHeight > 0 ? canvas.offsetHeight : 200;

    const ratio = Math.max(window.devicePixelRatio || 1, 1);

    // Simpan konten lama (jika ada)
    let data = null;
    if (signaturePad && !signaturePad.isEmpty()) {
        data = signaturePad.toData();
    }

    // Set ukuran fisik canvas (Pixel)
    canvas.width = width * ratio;
    canvas.height = height * ratio;

    // Scale context agar tulisan tajam
    canvas.getContext("2d").scale(ratio, ratio);

    // Kembalikan konten stroke (garis) lama jika ada
    if (signaturePad && data) {
        signaturePad.clear(); // Bersihkan dulu
        signaturePad.fromData(data); // Restore garis
    }
}

// 1. Inisialisasi Saat Modal Terbuka
$(document).on("shown.bs.modal", "#modalSignature", function () {
    // Paksa Tab "Draw" aktif
    $('.nav-tabs a[href="#tab-draw"]').tab("show");

    // Setup Library SignaturePad (Jika belum)
    if (!signaturePad) {
        if (window.SignaturePad) {
            signaturePad = new window.SignaturePad(canvas, {
                backgroundColor: "rgb(255, 255, 255)",
                penColor: "rgb(0, 0, 0)",
            });
        }
    }

    // Resize Canvas
    resizeCanvas();
    window.addEventListener("resize", resizeCanvas);

    // RESTORE DATA TTD DARI DATABASE
    let existingSignature = activeRow.find(".input-ttd-base64").val();

    // Reset Form
    $("#upload-signature").val("");
    $("#image-preview-container").hide();

    if (existingSignature && existingSignature.length > 100) {
        // 2. FIX KEPOTONG/ZOOM:
        // Saat restore gambar, kita harus beri tahu ukuran 'Logis' (CSS Width/Height)
        // Agar dia tidak digambar dobel skala (karena context sudah di-scale di resizeCanvas)

        // Hitung rasio layar saat ini
        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        signaturePad.fromDataURL(existingSignature, {
            ratio: ratio,
            width: canvas.width / ratio, // Gunakan ukuran CSS (Logis), bukan Pixel Fisik
            height: canvas.height / ratio,
        });

        // Set juga ke tab image
        $("#image-preview").attr("src", existingSignature);
        $("#image-preview-container").show();
    } else {
        signaturePad.clear();
        $("#image-preview").attr("src", "");
    }
});

// 2. FIX RESIZE SAAT PINDAH TAB
$(document).on("shown.bs.tab", 'a[data-toggle="tab"]', function (e) {
    if ($(e.target).attr("href") === "#tab-draw") {
        resizeCanvas();

        // Jika canvas terlihat kosong tapi ada data di database, muat ulang
        let existingSignature = activeRow.find(".input-ttd-base64").val();
        if (existingSignature && signaturePad.isEmpty()) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            signaturePad.fromDataURL(existingSignature, {
                ratio: ratio,
                width: canvas.width / ratio,
                height: canvas.height / ratio,
            });
        }
    }
});

// 3. Tombol Buka Modal
$(document).on("click", ".btn-open-signature", function () {
    activeRow = $(this).closest(".petugas-row");
    $("#modalSignature").modal("show");
});

// 4. Tombol Bersihkan
$(document).on("click", "#clear-signature", function () {
    if (signaturePad) signaturePad.clear();
});

// 5. Upload Gambar
$(document).on("change", "#upload-signature", function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#image-preview").attr("src", e.target.result);
            $("#image-preview-container").show();
        };
        reader.readAsDataURL(file);
    }
});

// 6. Simpan Tanda Tangan
$(document).on("click", "#save-signature", function () {
    let base64String = null;
    const activeTab = $("#modalSignature .nav-tabs .active a").attr("href");

    if (activeTab === "#tab-draw") {
        if (!signaturePad || signaturePad.isEmpty()) {
            alert("Canvas kosong!");
            return;
        }
        // Simpan gambar default (PNG)
        base64String = signaturePad.toDataURL("image/png");
    } else {
        const imgSrc = $("#image-preview").attr("src");
        if (!imgSrc || imgSrc === "") {
            alert("Silakan upload gambar!");
            return;
        }
        base64String = imgSrc;
    }

    if (activeRow && activeRow.length > 0) {
        activeRow.find(".input-ttd-base64").val(base64String);
        activeRow.find(".img-ttd-preview").attr("src", base64String).show();
        activeRow.find(".text-placeholder").hide();
        formChanged = true;
    }

    $("#modalSignature").modal("hide");
});

// ==========================================
// 5. INISIALISASI DATATABLES (YANG BARU)
// ==========================================
$(document).ready(function () {
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
});

// ==========================================
// 6. LOGIKA TANGGAL & HARI (AUTO & VALIDASI)
// ==========================================

// A. Validasi: Tanggal Periksa tidak boleh sebelum Tanggal Surat
$(document).on("change", "#tgl_surat", function () {
    var minDate = $(this).val();
    var tglPeriksa = $("#tgl_periksa");

    // Set atribut 'min' pada tanggal periksa
    tglPeriksa.attr("min", minDate);

    // Jika user sudah terlanjur isi tanggal periksa yang salah, reset
    if (tglPeriksa.val() && tglPeriksa.val() < minDate) {
        alert("Tanggal Pemeriksaan tidak boleh sebelum Tanggal Surat Tugas!");
        tglPeriksa.val(""); // Kosongkan
        $("#hari_periksa").val(""); // Kosongkan harinya juga
    }
});

// B. Bonus: Auto-fill Hari (Senin, Selasa, dll) saat Tanggal Periksa dipilih
$(document).on("change", "#tgl_periksa", function () {
    var dateVal = $(this).val();
    if (dateVal) {
        var date = new Date(dateVal);
        var days = [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
        ];
        var dayName = days[date.getDay()];

        $("#hari_periksa").val(dayName); // Isi otomatis ke input hari
    }
});

// Trigger saat halaman load (untuk mode Edit)
if ($("#tgl_surat").val()) {
    $("#tgl_periksa").attr("min", $("#tgl_surat").val());
}
