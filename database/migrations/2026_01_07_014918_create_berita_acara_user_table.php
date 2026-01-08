<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('berita_acara_user', function (Blueprint $table) {
            $table->id();

            // Tambahkan nama tabel 'berita_acara' secara eksplisit di sini
            $table->foreignId('berita_acara_id')->constrained('berita_acara')->cascadeOnDelete();

            // Untuk user_nip, pastikan kolom 'nip' di tabel users sudah menjadi PRIMARY atau UNIQUE
            $table->string('user_nip');
            $table->foreign('user_nip')->references('nip')->on('users')->cascadeOnDelete();
            $table->string('pangkat')->nullable();
            $table->string('jabatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara_user');
    }
};
