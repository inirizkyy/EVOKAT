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
            $table->enum('status_verifikator1', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('status');
            $table->text('catatan_verifikator1')->nullable()->after('status_verifikator1');
            $table->enum('status_verifikator2', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('catatan_verifikator1');
            $table->text('catatan_verifikator2')->nullable()->after('status_verifikator2');
            $table->enum('status_verifikator3', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('catatan_verifikator2');
            $table->text('catatan_verifikator3')->nullable()->after('status_verifikator3');
            $table->string('nomor_sk_kepengurusan')->nullable()->after('nomor_sk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn([
                'status_verifikator1',
                'catatan_verifikator1',
                'status_verifikator2',
                'catatan_verifikator2',
                'status_verifikator3',
                'catatan_verifikator3',
                'nomor_sk_kepengurusan',
            ]);
        });
    }
};
