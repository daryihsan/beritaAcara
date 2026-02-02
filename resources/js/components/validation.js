const $ = window.jQuery;

export function initValidation() {
    function checkRowLimit() {
        var rowCount = $(".petugas-row").length; // Hitung jumlah class .petugas-row

        if (rowCount <= 1) {
            $(".btn-hapus").hide(); // Sembunyikan semua tombol hapus jika sisa 1
        } else {
            $(".btn-hapus").show(); // Munculkan kembali jika lebih dari 1
        }
    }

    // --- 2. PANGGIL SAAT AWAL LOAD ---
    // Agar saat halaman edit dibuka, logika ini langsung jalan
    checkRowLimit();
    
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

    // Tombol next tab
    $(document).on("click", ".btn-next", function () {
        var currentTabId = "#" + $(this).closest(".tab-pane").attr("id");

        if (validateTab(currentTabId)) {
            $("#formAlert").hide();
            var nextTab = $(this).data("next");
            $('.nav-tabs a[href="' + nextTab + '"]').tab("show");
            $("html, body").animate({ scrollTop: 0 }, 300);
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

    // Validasi saat submit form
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

        // Jika valid, matikan proteksi unload untuk submit
        window.onbeforeunload = null;
    });

    // Auto-fill petugas
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

    // Tambah baris petugas
    $(document).on("click", "#btn-tambah-petugas", function () {
        var newRow = $(".petugas-row").first().clone();
        newRow.removeAttr("id");
        newRow.find("input").val("");
        newRow.find("textarea").val("");
        // Reset tanda tangan UI
        newRow.find(".img-ttd-preview").attr("src", "").hide();
        newRow.find(".text-placeholder").show();
        newRow.find(".input-ttd-base64").val("");
        newRow.find(".btn-hapus").removeAttr("disabled");
        $("#petugas-container").append(newRow);
        checkRowLimit();
    });

    // Hapus baris petugas
    $(document).on("click", ".btn-hapus", function () {
        if ($(".petugas-row").length > 1) {
            $(this).closest(".petugas-row").remove();
            
            // --- 4. PANGGIL SAAT HAPUS BARIS ---
            checkRowLimit();
        }
    });

    // Tanggal Periksa tidak boleh sebelum Tanggal Surat
    $(document).on("change", "#tgl_surat", function () {
        var minDate = $(this).val();
        var tglPeriksa = $("#tgl_periksa");

        // Set atribut 'min' pada tanggal periksa
        tglPeriksa.attr("min", minDate);

        // Jika user terlanjur isi tanggal periksa yang salah, reset
        if (tglPeriksa.val() && tglPeriksa.val() < minDate) {
            alert(
                "Tanggal Pemeriksaan tidak boleh sebelum Tanggal Surat Tugas!",
            );
            tglPeriksa.val("");
            $("#hari_periksa").val("");
        }
    });

    // Trigger saat halaman load
    if ($("#tgl_surat").val()) {
        $("#tgl_periksa").attr("min", $("#tgl_surat").val());
    }
    // Auto-fill hari saat Tanggal Periksa dipilih
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

            $("#hari_periksa").val(dayName);
        }
    });
}
