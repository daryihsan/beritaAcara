<script>
    // Notifikasi sukses (hijau)
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

    // Notifikasi system error (merah)
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

    // Validasi system error dari Controller yang pakai withErrors(['system_error'])
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
                    // Cari elemen form & loader
                    let form = document.getElementById(formId);
                    let loader = document.getElementById('global-loader');
                    let loaderCard = document.getElementById('loader-card');

                    // Validasi form 
                    if (!form) {
                        Swal.fire('Error!', 'Form hapus tidak ditemukan (ID Salah). Hubungi Developer.', 'error');
                        return;
                    }

                    if (loader) {
                        loader.classList.remove('hidden');
                        loader.classList.add('flex');

                        setTimeout(() => {
                            loader.classList.remove('opacity-0'); 
                            if (loaderCard) loaderCard.classList.remove('scale-95'); 
                        }, 10);
                    }

                    if (form) {
                        setTimeout(function () {
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
                // Tampilkan loading
                Swal.fire({
                    title: 'Logging out...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                // Submit form logout
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>