export function initLoader() {
    const loader = document.getElementById('global-loader');
    if (!loader) return;

    const showLoader = () => {
        loader.classList.remove('hidden');
        requestAnimationFrame(() => {
            loader.classList.remove('opacity-0');
            loader.querySelector('#loader-card').classList.remove('scale-95');
            loader.querySelector('#loader-card').classList.add('scale-100');
        });
        loader.classList.add('flex');
    };

    const hideLoader = () => {
        loader.classList.add('opacity-0');
        loader.querySelector('#loader-card').classList.remove('scale-100');
        loader.querySelector('#loader-card').classList.add('scale-95');
        
        // Tunggu animasi selesai (300ms) baru hide display
        setTimeout(() => {
            loader.classList.add('hidden');
            loader.classList.remove('flex');
        }, 300);
    };

    // handle klik link (Event Delegation)
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a'); // Cari elemen <a> terdekat

        if (link) {
            const href = link.getAttribute('href');
            const target = link.getAttribute('target');
            
            // Cek apakah user menekan Ctrl+Click atau Command+Click (Buka di tab baru)
            const isModifierKey = e.ctrlKey || e.metaKey; 

            // LOGIKA PENENTUAN:
            // 1. Harus link internal (diawali '/')
            // 2. Bukan link kosong ('#') atau javascript
            // 3. BUKAN target="_blank" (Tab baru)
            // 4. BUKAN tombol download
            // 5. BUKAN klik dengan Ctrl/Command
            
            const isPdfOrExport = href && (href.includes('pdf') || href.includes('export') || link.hasAttribute('download'));
            const isNewTab      = target === '_blank' || isModifierKey;
            const isInternal    = href && (href.startsWith('/') || href.startsWith(window.location.origin));
            const isSpecialLink = href.startsWith('#') || href.includes('javascript');

            // Prioritas 1: Apakah ini PDF atau Export? (Tidak peduli New Tab atau tidak)
            if (isPdfOrExport) {
                showLoader();
                // Matikan loader setelah 3 detik karena browser tidak refresh halaman
                setTimeout(hideLoader, 3000);
            }
            // Prioritas 2: Link Internal Biasa (Bukan New Tab, Bukan PDF)
            else if (isInternal && !isSpecialLink && !isNewTab) {
                showLoader();
                // Loader tidak perlu dimatikan, karena halaman akan refresh/pindah
            }
        }
    });

    // handle form submit
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.hasAttribute('data-no-loader')) return;
        if (!form.checkValidity()) return; // Jangan load kalau form tidak valid

        // Cek Tombol yang ditekan (Terutama untuk tombol Export Excel/PDF di header)
        const submitter = e.submitter; 
        
        let isExport = false;

        // Cek 1: Apakah tombol yang diklik punya 'formaction' export/pdf?
        if (submitter && submitter.hasAttribute('formaction')) {
            const action = submitter.getAttribute('formaction');
            if (action.includes('export') || action.includes('pdf')) {
                isExport = true;
            }
        }

        // Cek 2: Apakah action form utamanya export/pdf?
        if (form.action && (form.action.includes('export') || form.action.includes('pdf'))) {
            isExport = true;
        }

        // Cek 3: Apakah targetnya _blank?
        const isNewTab = form.target === '_blank';

        if (isExport || isNewTab) {
            // Kasus Download/Export:
            // Tampilkan loader sebentar (biar user tau sistem merespon), lalu hilangkan.
            showLoader();
            setTimeout(hideLoader, 2000); 
        } else {
            // Kasus Simpan Biasa:
            // Tampilkan loader sampai halaman terreload
            showLoader();
        }
    });

    // back button (Safari/Chrome bfcache)
    // Kalau user klik Back, loader harus hilang
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoader();
        }
    });

    // Expose Global
    window.hideGlobalLoader = hideLoader;
    window.showGlobalLoader = showLoader;
}