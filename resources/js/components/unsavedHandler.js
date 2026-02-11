const Swal = window.Swal;
const $ = window.jQuery;

export function initUnsavedHandler() {
    let isFormDirty = false;

    // Deteksi perubahan di form (ketik/pilih)
    // Abaikan form search atau delete atau filter dashboard admin
    $('form:not([id^="delete-form"]):not(#logout-form):not(#filter-form)').on(
        "change input",
        function () {
            isFormDirty = true;
        },
    );

    // Jika tombol submit/simpan ditekan, matikan alarm
    $("form").on("submit", function () {
        isFormDirty = false;
    });

    // Intercept klik link (sidebar/menu)
    $("a").on("click", function (e) {
        let targetUrl = $(this).attr("href");
        let toggleAttr = $(this).attr("data-toggle"); // Cek apakah ini tab bootstrap?

        // Pengecualian
        if (
            !targetUrl ||
            targetUrl.startsWith("#") ||
            targetUrl.startsWith("javascript")
        )
            return;
        if (toggleAttr === "tab" || toggleAttr === "pill") return;
        if (
            $(this).attr("onclick") &&
            $(this).attr("onclick").includes("confirmLogout")
        )
            return;

        // Jika form Kotor (ada perubahan belum save)
        if (isFormDirty) {
            e.preventDefault(); // Tahan dulu
            e.stopPropagation();

            Swal.fire({
                title: "Data Belum Disimpan!",
                text: "Perubahan yang Anda buat akan hilang jika berpindah halaman.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Tetap Pindah",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    isFormDirty = false;
                    window.location.href = targetUrl;
                } else {
                    let loader = document.getElementById("global-loader");

                    if (loader) {
                        loader.classList.add("opacity-0");

                        // Tunggu animasi fade-out selesai (300ms), baru hidden
                        setTimeout(() => {
                            loader.classList.remove("flex");
                            loader.classList.add("hidden");

                            // Reset card scale juga
                            let card = document.getElementById("loader-card");
                            if (card) card.classList.add("scale-95");
                        }, 300);
                    }

                    $(".preloader").hide();
                    $("#loader").hide();
                    $(".loader").hide();
                }
            });
        }
    });

    // Handling refresh / close tab
    window.addEventListener("beforeunload", function (e) {
        if (isFormDirty) {
            e.preventDefault();
            e.returnValue = ""; 
        }
    });
}
