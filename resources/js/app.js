import './bootstrap';

// ==========================================
// 1. LOGIKA VALIDASI FORM & TAB (jQuery)
// ==========================================

function validateTab(tabId) {
    let isValid = true;
    let tabPane = $(tabId);
    let tabLink = $('.nav-tabs a[href="' + tabId + '"]').parent();

    tabPane.find("input[required], textarea[required], select[required]").each(function () {
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
            setTimeout(function () { $("#formAlert").fadeOut(); }, 3000);
            
            // Fokus ke error pertama
            $(currentTabId).find("input[required], textarea[required]").filter(function () {
                return !$(this).val();
            }).first().focus();
        }
    });

    // Validasi saat Submit Form
    $("#formBeritaAcara").on("submit", function (e) {
        let formIsValid = true;
        let firstErrorTab = null;
        let isAnyFilled = false;

        // Cek apakah ada isian sama sekali (untuk logika 'form changed')
        $(this).find("input[required], textarea[required]").each(function() {
            if ($(this).val().trim() !== "") {
                isAnyFilled = true;
                return false; 
            }
        });

        // Validasi tiap tab
        $(".tab-pane").each(function() {
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

$(document).on("input change", "#formBeritaAcara input, #formBeritaAcara textarea, #formBeritaAcara select", function() {
    formChanged = true;
});

window.onbeforeunload = function(e) {
    if (formChanged) {
        e.preventDefault();
        return "Data belum disimpan!"; 
    }
};

$('form[action="/logout"]').on("submit", function(e) {
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
// 3. LOGIKA SIDEBAR (PERBAIKAN UTAMA)
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('berita-acara-toggle');
    
    // Cek keberadaan tombol agar tidak error di halaman Login
    if (toggleButton) {
        const submenuId = 'berita-acara-submenu';
        const iconId = 'berita-acara-icon';
        const storageKey = 'berita-acara-open';
        
        const submenu = document.getElementById(submenuId);
        const icon = document.getElementById(iconId);

        // --- PERBAIKAN DI SINI ---
        // Kita ganti PHP {{ request()->... }} dengan Javascript window.location
        const currentPath = window.location.pathname;
        const currentSearch = window.location.search;

        // Cek apakah URL mengandung 'berita-acara' ATAU ada parameter '?tahun='
        const isSubActive = currentPath.includes('berita-acara') || currentSearch.includes('tahun');
        
        const storedState = localStorage.getItem(storageKey);
        
        // Buka jika URL aktif ATAU user terakhir kali membiarkannya terbuka
        const shouldBeOpen = isSubActive || (storedState === 'true');

        if (shouldBeOpen) {
            submenu.style.transition = 'none';
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
            icon.classList.add('rotate-180');
            
            setTimeout(() => {
                submenu.style.transition = 'max-height 0.3s ease-in-out';
            }, 50);
        }

        toggleButton.addEventListener('click', function(e) {
            const isOpen = submenu.style.maxHeight !== '0px' && submenu.style.maxHeight !== '';
            
            if (isOpen) {
                submenu.style.maxHeight = '0px';
                icon.classList.remove('rotate-180');
                localStorage.setItem(storageKey, 'false');
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                icon.classList.add('rotate-180');
                localStorage.setItem(storageKey, 'true');
            }
        });
    }
});