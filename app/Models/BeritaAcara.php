<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    // Tambahkan baris ini untuk menentukan nama tabel secara manual
    protected $table = 'berita_acara';

    protected $fillable = [
        'no_surat_tugas',
        'tgl_surat_tugas',
        'tanggal_pemeriksaan',
        'hari',
        'objek_nama',
        'objek_alamat',
        'hasil_pemeriksaan',
        'created_by',
        'kepala_balai_text', // Tambahkan ini
        'objek_kota',        // Tambahkan ini
        'dalam_rangka',      // Tambahkan ini
        'yang_diperiksa'     // Tambahkan ini
    ];

    // app/Models/BeritaAcara.php

    public function petugas()
    {
        return $this->belongsToMany(
            User::class,
            'berita_acara_user',
            'berita_acara_id',
            'user_nip',
            'id',
            'nip'
        )->withPivot('pangkat', 'jabatan');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
