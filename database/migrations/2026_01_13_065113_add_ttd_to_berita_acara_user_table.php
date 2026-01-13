<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('berita_acara_user', function (Blueprint $table) {
            // Kolom TEXT karena string base64 bisa sangat panjang
            $table->longText('ttd')->nullable()->after('jabatan'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita_acara_user', function (Blueprint $table) {
            $table->dropColumn('ttd');
        });
    }
};
