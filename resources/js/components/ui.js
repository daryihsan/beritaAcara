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

    // buka dari navbar
    btnOpen.on("click", function (e) {
        e.preventDefault();
        openSidebar();
    });

    // tutup dari floating button
    floatBtn.on("click", function () {
        closeSidebar();
    });

    // tutup dari overlay
    overlay.on("click", function () {
        closeSidebar();
    });

    // dropdown berita acara
    // $("#berita-acara-toggle").on("click", function (e) {
    //     e.preventDefault();
    //     $("#berita-acara-submenu").toggleClass("max-h-0 max-h-[500px]");
    //     $("#berita-acara-icon").toggleClass("rotate-180");
    // });

    const toggleButton = $("#berita-acara-toggle");
    const submenu = $("#berita-acara-submenu");
    const icon = $("#berita-acara-icon");
    const storageKey = "berita-acara-open";

    // Cek keberadaan tombol agar tidak error di halaman Login
    if (toggleButton.length) {
        // const submenu = document.getElementById(submenuId);
        // const icon = document.getElementById(iconId);

        // --- PERBAIKAN DI SINI ---
        // Kita ganti PHP {{ request()->... }} dengan Javascript window.location
        const currentPath = window.location.pathname;
        const currentSearch = window.location.search;

        // Cek apakah URL mengandung 'berita-acara' ATAU ada parameter '?tahun='
        const isSubActive =
            currentPath.includes("berita-acara") ||
            currentSearch.includes("tahun");

        const storedState = localStorage.getItem(storageKey);

        // // Buka jika URL aktif ATAU user terakhir kali membiarkannya terbuka
        // const shouldBeOpen = isSubActive || storedState === "true";

        // if (shouldBeOpen) {
        //     submenu.css({
        //         "max-height": submenu.prop("scrollHeight") + "px",
        //         "transition": "none"
        //     });
        //     icon.addClass("rotate-180");

        //     setTimeout(() => {
        //         submenu.css("transition", "max-height 0.3s ease-in-out");
        //     }, 50);
        // }

        if (isSubActive || storedState === "true") {
            // FIX: Gunakan .css() dan .prop() karena ini objek jQuery
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
}