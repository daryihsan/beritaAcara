<script>
    // 1. Notifikasi SUKSES (Hijau) - Dari ->with('success', ...)
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Oke',
            timer: 2000 // Tutup otomatis biar cepat
        });
    @endif

    // 2. Notifikasi SYSTEM ERROR (Merah) - Dari ->with('error', ...)
    // Kita tangkap session 'error' khusus, bukan $errors validasi
    @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Tutup'
        });
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
        event.preventDefault(); // Cegah submit langsung
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>