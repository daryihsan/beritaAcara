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
            $table->string('no_surat_tugas');
            $table->date('tgl_surat_tugas');
            $table->date('tanggal_pemeriksaan');
            $table->string('hari');
            $table->string('objek_nama');
            $table->text('objek_alamat');
            $table->text('hasil_pemeriksaan');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
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
