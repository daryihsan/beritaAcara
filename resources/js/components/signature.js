const $ = window.jQuery; 
import SignaturePad from 'signature_pad';

export function initSignature() {
    let activeRow = null;
    let signaturePad = null;
    const canvas = document.getElementById("signature-pad");

    // Fungsi Resize Canvas
    function resizeCanvas() {
        // Jika tinggi terbaca 0 (karena modal belum full load), paksa tinggi default 200px
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
            signaturePad.clear(); 
            signaturePad.fromData(data); 
        }
    }

    // Inisialisasi Saat Modal Terbuka
    $(document).on("shown.bs.modal", "#modalSignature", function () {
        // Paksa Tab "Draw" aktif
        $('.nav-tabs a[href="#tab-draw"]').tab("show");

        // Setup Library SignaturePad
        if (!signaturePad) {
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: "rgb(255, 255, 255)",
                penColor: "rgb(0, 0, 0)",
            });
        }

        // Resize Canvas
        resizeCanvas();
        window.addEventListener("resize", resizeCanvas);

        // Restore Data TTD dari Database
        let existingSignature = activeRow.find(".input-ttd-base64").val();

        // Reset Form
        $("#upload-signature").val("");
        $("#image-preview-container").hide();

        if (existingSignature && existingSignature.length > 100) {
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

    // Resize Saat Pindah Tab
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

    // Tombol Buka Modal
    $(document).on("click", ".btn-open-signature", function () {
        activeRow = $(this).closest(".petugas-row");
        $("#modalSignature").modal("show");
    });

    // Tombol Bersihkan
    $(document).on("click", "#clear-signature", function () {
        if (signaturePad) signaturePad.clear();
    });

    // Upload Gambar
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

    // Simpan Tanda Tangan
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
            activeRow.closest('form').trigger('change');
        }

        $("#modalSignature").modal("hide");
    });
}