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
     * Bisa LIHAT atau CETAK PDF 
     */
    public function view(User $user, BeritaAcara $beritaAcara): Response
    {
        // Admin
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Bukan admin, cek apakah NIP-nya ada di daftar petugas BAP ini.
        return $beritaAcara->petugas->contains('nip', $user->nip)
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk melihat dokumen ini karena nama Anda tidak tercantum sebagai petugas.');
    }

    /**
     * Bisa EDIT/UPDATE
     */
    public function update(User $user, BeritaAcara $beritaAcara): Response
    {
        // Logic 'view' 
        $response = $this->view($user, $beritaAcara);

        return $response->allowed()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk mengedit dokumen ini karena nama Anda tidak tercantum sebagai petugas.');
    }

    /**
     * Bisa HAPUS
     */
    public function delete(User $user, BeritaAcara $beritaAcara): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Anda tidak memiliki akses untuk menghapus dokumen ini.');
    }
}