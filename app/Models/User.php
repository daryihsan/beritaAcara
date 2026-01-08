<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nip',
        'name',
        'pangkat',
        'jabatan',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function beritaAcaras()
    {
        // Karena kamu pakai NIP (string) sebagai kunci di pivot, kita definisikan manual:
        return $this->belongsToMany(
            BeritaAcara::class,
            'berita_acara_user', // nama tabel pivot
            'user_nip',          // foreign key di tabel pivot untuk model ini
            'berita_acara_id',   // foreign key di tabel pivot untuk model lawan
            'nip',               // local key di tabel users
            'id'                 // local key di tabel berita_acara
        );
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
