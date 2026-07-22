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
            // Verifikator 4 fields (after catatan_verifikator3)
            $table->enum('status_verifikator4', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('catatan_verifikator3');
            $table->text('catatan_verifikator4')->nullable()->after('status_verifikator4');

            // Surat pengantar organisasi fields
            $table->string('nomor_surat_pengantar')->nullable()->after('email_organisasi');
            $table->date('tanggal_surat_pengantar')->nullable()->after('nomor_surat_pengantar');
            $table->string('perihal_surat_pengantar')->nullable()->after('tanggal_surat_pengantar');
            $table->string('file_surat_pengantar')->nullable()->after('perihal_surat_pengantar');
            $table->string('file_sk_pendirian')->nullable()->after('file_surat_pengantar');
            $table->string('file_sk_kepengurusan_pdf')->nullable()->after('file_sk_pendirian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn([
                'status_verifikator4',
                'catatan_verifikator4',
                'nomor_surat_pengantar',
                'tanggal_surat_pengantar',
                'perihal_surat_pengantar',
                'file_surat_pengantar',
                'file_sk_pendirian',
                'file_sk_kepengurusan_pdf',
            ]);
        });
    }
};
