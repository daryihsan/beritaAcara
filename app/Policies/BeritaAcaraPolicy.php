<?php

namespace App\Policies;

use App\Models\BeritaAcara;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BeritaAcaraPolicy
{
    use HandlesAuthorization;

    /**
     * Logic: Siapa yang boleh LIHAT atau CETAK PDF dokumen ini?
     * Aturan: Admin BOLEH, Petugas BOLEH (tapi cuma dokumen dia sendiri).
     */
    public function view(User $user, BeritaAcara $beritaAcara): Response
    {
        // 1. Jika dia Admin, lolos.
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Cek Petugas
        if ($beritaAcara->petugas->contains('nip', $user->nip)) {
            return Response::allow();
        }

        // 2. Jika bukan Admin, cek apakah NIP-nya ada di daftar petugas BAP ini.
        // Kita pakai 'contains' untuk cek di collection relasi.
        return $beritaAcara->petugas->contains('nip', $user->nip)
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk melihat dokumen ini karena nama Anda tidak tercantum sebagai petugas.');
    }

    /**
     * Logic: Siapa yang boleh EDIT/UPDATE dokumen ini?
     * Aturan: Sama persis dengan 'view'.
     */
    public function update(User $user, BeritaAcara $beritaAcara): Response
    {
        // Kita panggil logic 'view' di atas biar ga nulis ulang
        $response = $this->view($user, $beritaAcara);

        return $response->allowed()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk mengedit dokumen ini karena nama Anda tidak tercantum sebagai petugas.');
    }

    /**
     * Logic: Siapa yang boleh HAPUS dokumen ini?
     * Aturan: HANYA ADMIN. Petugas tidak boleh hapus.
     */
    public function delete(User $user, BeritaAcara $beritaAcara): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk menghapus dokumen ini.');
    }
}