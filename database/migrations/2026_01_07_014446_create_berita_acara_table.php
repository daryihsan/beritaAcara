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
            $table->id();
            $table->string('no_surat_tugas')->index();
            $table->date('tgl_surat_tugas');
            $table->date('tanggal_pemeriksaan');
            $table->string('hari');
            $table->text('kepala_balai_text')->nullable();
            $table->string('objek_nama')->index();
            $table->text('objek_alamat');
            $table->string('objek_kota')->nullable();
            $table->text('dalam_rangka')->nullable();
            $table->text('hasil_pemeriksaan');
            $table->string('yang_diperiksa')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['tanggal_pemeriksaan', 'created_at']);
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
