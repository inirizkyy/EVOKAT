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
        Schema::table('permohonans', function (Blueprint $table) {
            $table->string('hari_verifikasi_fisik')->nullable()->after('catatan');
            $table->date('tanggal_verifikasi_fisik')->nullable()->after('hari_verifikasi_fisik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn(['hari_verifikasi_fisik', 'tanggal_verifikasi_fisik']);
        });
    }
};
