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
        Schema::create('buku_registrasi_advokats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemohon_id')->constrained('pemohons')->onDelete('cascade');
            $table->foreignId('permohonan_id')->constrained('permohonans')->onDelete('cascade');
            $table->string('nomor_bas')->nullable();
            $table->date('tanggal_disumpah')->nullable();
            $table->string('ketua_pengadilan_tinggi')->nullable();
            $table->string('saksi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_registrasi_advokats');
    }
};
