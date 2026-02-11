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
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('no_surat_tugas');
            $table->date('tgl_surat_tugas');
            $table->date('tanggal_pemeriksaan');
            $table->string('hari');
            $table->text('kepala_balai_text');
            $table->string('objek_nama');
            $table->text('objek_alamat');
            $table->string('objek_kota');
            $table->text('dalam_rangka');
            $table->text('hasil_pemeriksaan');
            $table->string('yang_diperiksa');
            $table->string('file_pengesahan')->nullable();
            $table->foreignUlid('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tanggal_pemeriksaan', 'created_at']);
            $table->index(['no_surat_tugas', 'objek_nama', 'tanggal_pemeriksaan'], 'idx_pencarian_utama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara');
    }
};
