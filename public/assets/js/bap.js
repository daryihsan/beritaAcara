$(document).on("click", ".btn-next", function () {
    var currentTab = $(this).closest(".tab-pane");
    var isValid = true;

    currentTab
        .find("input[required], textarea[required], select[required]")
        .each(function () {
            if ($(this).val().trim() === "") {
                isValid = false;
                $(this).focus();
                return false;
            }
        });
    if (!isValid) {
        $("#formAlert").fadeIn();

        setTimeout(function () {
            $("#formAlert").fadeOut();
        }, 3000);
        return;
    }
    var nextTab = $(this).data("next");
    $('.nav-tabs a[href="' + nextTab + '"]').tab("show");
});

$("form").on("submit", function (e) {
    $("#formAlertGlobal").hide();
    var firstInvalid = null;
    $(this)
        .find("[required]")
        .each(function () {
            if ($(this).val().trim() === "") {
                firstInvalid = $(this);
                return false;
            }
        });

    if (firstInvalid) {
        e.preventDefault();
        // Tampilkan alert
        $("#formAlertGlobal").fadeIn();
        // Cari tab tempat field kosong
        var tabPane = firstInvalid.closest(".tab-pane").attr("id");
        // Pindah ke tab tersebut
        $('.nav-tabs a[href="#' + tabPane + '"]').tab("show");
        // Fokus ke field
        setTimeout(function () {
            firstInvalid.focus();
        }, 300);
        return false;
    }
});

$("#btn-tambah-petugas").click(function () {
    var html = `
            <div class="petugas-row">
                <div class="row">
                    <div class="col-md-4">
                        <label>Nama Petugas</label>
                        <input type="text" name="petugas_nama[]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Pangkat/Gol</label>
                        <input type="text" name="petugas_pangkat[]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>NIP</label>
                        <input type="text" name="petugas_nip[]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Aksi</label><br>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus">Hapus</button>
                    </div>
                </div>
                <div class="row" style="margin-top:5px;">
                    <div class="col-md-12">
                        <input type="text" name="petugas_jabatan[]" class="form-control" placeholder="Jabatan">
                    </div>
                </div>
            </div>`;
    $("#petugas-container").append(html);
});

$(document).on("click", ".btn-hapus", function () {
    $(this).closest(".petugas-row").remove();
});
