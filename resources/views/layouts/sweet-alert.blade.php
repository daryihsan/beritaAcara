<script>
    // 1. Notifikasi SUKSES (Hijau) - Dari ->with('success', ...)
    @if(session('success'))
        setTimeout(() => {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Oke',
                timer: 10000
            });
        }, 500);
    @endif

    // 2. Notifikasi SYSTEM ERROR (Merah) - Dari ->with('error', ...)
    // Kita tangkap session 'error' khusus, bukan $errors validasi
    @if(session('error'))
        setTimeout(() => {
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Tutup'
            });
        }, 500);
    @endif
    
    // 3. (OPSIONAL) Validasi System Error dari Controller yang pakai withErrors(['system_error'])
    // Ini buat nangkep error fatal yang kita lempar dari Controller tadi
    @if($errors->has('system_error'))
        Swal.fire({
            title: 'Terjadi Kesalahan!',
            text: "{{ $errors->first('system_error') }}",
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Tutup'
        });
    @endif

    function confirmDelete(event, formId) {
        event.preventDefault(); 
        event.stopPropagation();
        
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // 1. Cari Elemen Form & Loader
                let form = document.getElementById(formId);
                let loader = document.getElementById('global-loader');
                let loaderCard = document.getElementById('loader-card');

                // 2. Validasi Form Ada
                if (!form) {
                    Swal.fire('Error!', 'Form hapus tidak ditemukan (ID Salah). Hubungi Developer.', 'error');
                    return;
                }

                if (loader) {
                    // 1. Hapus class 'hidden' & tambah 'flex' (Biar ada di layout dulu)
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');

                    // 2. Trik Timeout Kecil: Biar browser sadar elemennya sudah ada, baru kita fade-in
                    setTimeout(() => {
                        loader.classList.remove('opacity-0'); // Efek Fade In
                        if(loaderCard) loaderCard.classList.remove('scale-95'); // Efek Pop Up
                    }, 10);
                }

                if (form) {
                    // 3. Jeda sedikit lebih lama (300ms) agar animasi loader kelihatan user dulu
                    // baru form dikirim. Ini menghilangkan "Jeda Canggung".
                    setTimeout(function() {
                        form.submit();
                    }, 300);
                }
            }
        });
    }

    function confirmLogout(event) {
        event.preventDefault();
        
        Swal.fire({
            title: 'Keluar Aplikasi?',
            text: "Sesi Anda akan diakhiri.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan Loading
                Swal.fire({
                    title: 'Logging out...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                // Submit form logout (sesuaikan ID form logoutmu)
                document.getElementById('logout-form').submit(); 
            }
        });
    }
</script>