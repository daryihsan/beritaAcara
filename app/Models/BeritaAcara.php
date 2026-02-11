<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class BeritaAcara extends Model
{
    use SoftDeletes, LogsActivity, HasUlids;
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
        'kepala_balai_text',
        'objek_kota',
        'dalam_rangka',
        'yang_diperiksa',
        'file_pengesahan'
    ];

    public function petugas()
    {
        return $this->belongsToMany(
            User::class,
            'berita_acara_user',
            'berita_acara_id',
            'user_nip',
            'id',
            'nip'
        )->withPivot('pangkat', 'jabatan', 'ttd');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['no_surat_tugas', 'objek_nama', 'hasil_pemeriksaan', 'tanggal_pemeriksaan']) // Kolom yang dipantau
            ->logOnlyDirty() // Catat yang berubah
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Berita Acara telah di-{$eventName}");
    }
}
