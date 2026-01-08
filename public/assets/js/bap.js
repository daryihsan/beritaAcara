function validateTab(tabId) {
    let isValid = true;
    let tabPane = $(tabId);
    let tabLink = $('.nav-tabs a[href="' + tabId + '"]').parent();

    tabPane
        .find("input[required], textarea[required], select[required]")
        .each(function () {
            if ($(this).val() === null || $(this).val().trim() === "") {
                isValid = false;
                $(this).css("border-color", "#fb7185"); // Warna merah pada input
            } else {
                $(this).css("border-color", "#e2e8f0"); // Reset warna
            }
        });

    if (isValid) {
        // Hilangkan merah, ganti hijau
        tabLink.removeClass("tab-error").addClass("tab-success");
    } else {
        // Hilangkan hijau, ganti merah
        tabLink.removeClass("tab-success").addClass("tab-error");
    }

    return isValid;
}

$(document).on("click", ".btn-next", function () {
    var currentTabId = "#" + $(this).closest(".tab-pane").attr("id");

    // Gunakan fungsi validateTab yang sudah ada
    if (validateTab(currentTabId)) {
        $("#formAlert").hide();
        var nextTab = $(this).data("next");
        $('.nav-tabs a[href="' + nextTab + '"]').tab("show");
    } else {
        $("#formAlert").fadeIn();
        setTimeout(function () {
            $("#formAlert").fadeOut();
        }, 3000);

        // Fokus ke input pertama yang kosong di tab ini
        $(currentTabId)
            .find("input[required], textarea[required]")
            .filter(function () {
                return !$(this).val();
            })
            .first()
            .focus();
    }
});

$("form").on("submit", function (e) {
    let formIsValid = true;
    let firstErrorTab = null;

    // Cek validasi semua tab
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
});

$(document).on("click", ".btn-hapus", function () {
    $(this).closest(".petugas-row").remove();
});

$(document).ready(function () {
    $(document).on("input", ".input-nama", function () {
        var val = $(this).val();
        var row = $(this).closest(".petugas-row");

        // Cari data di datalist yang namanya cocok dengan yang diketik
        var option = $("#list-petugas option").filter(function () {
            return $(this).val() === val;
        });

        if (option.length) {
            // Jika ketemu, isi semua kolom secara otomatis
            row.find(".input-nip").val(option.data("nip"));
            row.find(".input-pangkat").val(option.data("pangkat"));
            row.find(".input-jabatan").val(option.data("jabatan"));
        } else {
            // Jika nama dihapus atau tidak cocok, kosongkan kolom lainnya
            row.find(".input-nip, .input-pangkat, .input-jabatan").val("");
        }
    });

    // Logika tambah & hapus baris tetap sama seperti sebelumnya
    $("#btn-tambah-petugas").click(function () {
        var newRow = $("#row-0").clone();
        newRow.find("input").val("");
        newRow.find(".btn-hapus").removeAttr("disabled");
        $("#petugas-container").append(newRow);
    });

    $(document).on("click", ".btn-hapus", function () {
        $(this).closest(".petugas-row").remove();
    });
});
