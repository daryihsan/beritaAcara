/**
 * Logika Modal Upload & Preview Dokumen Sah BAP
 */
export function initBapUpload() {
    // Tempelkan fungsi ke window agar bisa dipanggil onclick
    window.openUploadModal = (id, noSurat) => {
        const form = document.getElementById("formUpload");
        // Sesuaikan URL-nya dengan route baru: /berita-acara/{id}/upload
        form.action = `/berita-acara/${id}/upload`;

        document.getElementById("uploadNoSurat").innerText = noSurat;
        document.getElementById("uploadModal").classList.remove("hidden");
    };

    window.handleFilePreview = (input) => {
        const imagePreview = document.getElementById("imagePreview");
        const pdfPreview = document.getElementById("pdfPreview");
        const previewIcon = document.getElementById("previewIcon");
        const fileNameDisplay = document.getElementById("fileNameDisplay");
        const fileNameText = document.getElementById("fileNameText");

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Tampilkan nama file
            fileNameText.innerText = file.name;
            fileNameDisplay.classList.remove("hidden");
            fileNameDisplay.classList.add("flex");

            document.getElementById("instructionText").innerText = "Ganti file";

            if (file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove("hidden");
                    pdfPreview.classList.add("hidden");
                    previewIcon.classList.add("hidden");
                };
                reader.readAsDataURL(file);
            } else if (file.type === "application/pdf") {
                imagePreview.classList.add("hidden");
                pdfPreview.classList.remove("hidden");
                pdfPreview.classList.add("flex");
                previewIcon.classList.add("hidden");
            }
        }
    };

    window.closeUploadModal = () => {
        const modal = document.getElementById("uploadModal");
        modal.classList.add("hidden");

        // Reset semua elemen
        document.getElementById("imagePreview").src = "";
        document.getElementById("imagePreview").classList.add("hidden");
        document.getElementById("pdfPreview").classList.add("hidden");
        document.getElementById("pdfPreview").classList.remove("flex");
        document.getElementById("previewIcon").classList.remove("hidden");

        const fileNameDisplay = document.getElementById("fileNameDisplay");
        fileNameDisplay.classList.add("hidden");
        fileNameDisplay.classList.remove("flex");

        document.getElementById("fileNameText").innerText = "";
        document.getElementById("instructionText").innerText =
            "Klik untuk memilih file";
        document.getElementById("formUpload").reset();
    };
}
