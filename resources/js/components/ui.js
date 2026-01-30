const $ = window.jQuery;

export function initUI() {
    const sidebar = $("#sidebar-menu");
    const overlay = $("#sidebar-overlay");
    const btnOpen = $("#btn-open-sidebar");
    const floatBtn = $("#sidebar-float-toggle");

    function openSidebar() {
        sidebar.removeClass("-translate-x-full");
        overlay.removeClass("hidden");
        floatBtn.removeClass("-translate-x-full");

        $("body").addClass("overflow-hidden sidebar-open");
    }

    function closeSidebar() {
        sidebar.addClass("-translate-x-full");
        overlay.addClass("hidden");
        floatBtn.addClass("-translate-x-full");

        $("body").removeClass("overflow-hidden sidebar-open");
    }

    // Buka dari navbar
    btnOpen.on("click", function (e) {
        e.preventDefault();
        openSidebar();
    });

    // Tutup dari floating button
    floatBtn.on("click", function () {
        closeSidebar();
    });

    // Tutup dari overlay
    overlay.on("click", function () {
        closeSidebar();
    });

    const toggleButton = $("#berita-acara-toggle");
    const submenu = $("#berita-acara-submenu");
    const icon = $("#berita-acara-icon");
    const storageKey = "berita-acara-open";

    // Cek keberadaan tombol
    if (toggleButton.length) {
        const currentPath = window.location.pathname;
        const currentSearch = window.location.search;

        // Cek apakah URL mengandung 'berita-acara' ATAU ada parameter '?tahun='
        const isSubActive =
            currentPath.includes("berita-acara") ||
            currentSearch.includes("tahun");

        const storedState = localStorage.getItem(storageKey);

        if (isSubActive || storedState === "true") {
            // Gunakan .css() dan .prop() karena objek jQuery
            let scrollHeight = submenu.prop("scrollHeight");
            submenu.css("max-height", scrollHeight + "px");
            submenu.css("transition", "none");
            icon.addClass("rotate-180");

            setTimeout(() => {
                submenu.css("transition", "max-height 0.3s ease-in-out");
            }, 50);
        }

        toggleButton.on("click", function (e) {
            e.preventDefault();
            const currentHeight = submenu.css("max-height");
            const isOpen = currentHeight !== "0px" && currentHeight !== "none";

            if (isOpen) {
                submenu.css("max-height", "0px");
                icon.removeClass("rotate-180");
                localStorage.setItem(storageKey, "false");
            } else {
                let scrollHeight = submenu.prop("scrollHeight");
                submenu.css("max-height", scrollHeight + "px");
                icon.addClass("rotate-180");
                localStorage.setItem(storageKey, "true");
            }
        });
    }

    // Proteksi unload form jika ada perubahan
    let formChanged = false;

    $(document).on(
        "input change",
        "#formBeritaAcara input, #formBeritaAcara textarea, #formBeritaAcara select",
        function () {
            formChanged = true;
        },
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
}
