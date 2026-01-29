const Swal = window.Swal;
const $ = window.jQuery;

export function initUnsavedHandler() {
    let isFormDirty = false;

    // 1. Deteksi Perubahan di Form (Ketik/Pilih)
    // Abaikan form search atau delete agar tidak false alarm
    $('form:not([id^="delete-form"]):not(#logout-form)').on('change input', function() {
        isFormDirty = true;
    });

    // 2. Jika Tombol Submit/Simpan ditekan, matikan alarm
    $('form').on('submit', function() {
        isFormDirty = false;
    });

    // 3. INTERCEPT KLIK LINK (Sidebar/Menu) - Masalah 3 & 4
    // 3. INTERCEPT KLIK LINK (Sidebar/Menu)
    $('a').on('click', function(e) {
        let targetUrl = $(this).attr('href');
        let toggleAttr = $(this).attr('data-toggle'); // Cek apakah ini Tab Bootstrap?

        // === PENGECUALIAN (JANGAN CEGAT INI) ===
        if (!targetUrl || targetUrl.startsWith('#') || targetUrl.startsWith('javascript')) return;
        if (toggleAttr === 'tab' || toggleAttr === 'pill') return;
        if ($(this).attr('onclick') && $(this).attr('onclick').includes('confirmLogout')) return;

        // === EKSEKUSI CEGAT ===
        // Jika Form Kotor (Ada perubahan belum save)
        if (isFormDirty) {
            e.preventDefault(); // Tahan dulu
            e.stopPropagation();

            Swal.fire({
                title: 'Data Belum Disimpan!',
                text: "Perubahan yang Anda buat akan hilang jika berpindah halaman.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tetap Pindah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    isFormDirty = false; // Reset status
                    window.location.href = targetUrl; // Lanjut pindah manual
                } else {
                    let loader = document.getElementById('global-loader');
                    
                    if (loader) {
                        // Kembalikan ke state awal (Tersembunyi & Transparan)
                        loader.classList.add('opacity-0'); 
                        
                        // Tunggu animasi fade-out selesai (300ms), baru hidden
                        setTimeout(() => {
                            loader.classList.remove('flex');
                            loader.classList.add('hidden');
                            
                            // Reset card scale juga
                            let card = document.getElementById('loader-card');
                            if(card) card.classList.add('scale-95');
                        }, 300);
                    }
                    
                    // Jaga-jaga matikan loader template lain
                    $('.preloader').hide();
                    $('#loader').hide();
                    // User pilih BATAL (DIAM DI TEMPAT):
                    // === FIX UTAMA DISINI ===
                    // Kita cari semua kemungkinan ID loader dan paksa sembunyi
                    $('.loader').hide();         // Loader Class umum
                }
            });
        }
    });

    // 4. HANDLING REFRESH / CLOSE TAB (Masalah 5)
    // Browser memaksakan alert bawaan di sini. Kita tidak bisa pakai SweetAlert.
    // Tapi kita bisa pastikan setidaknya alertnya muncul jika data belum disave.
    window.addEventListener('beforeunload', function (e) {
        if (isFormDirty) {
            e.preventDefault();
            e.returnValue = ''; // Memicu alert bawaan browser
        }
    });
}