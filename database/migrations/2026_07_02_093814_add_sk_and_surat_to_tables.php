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
        Schema::table('pemohons', function (Blueprint $table) {
            $table->string('nomor_sk')->after('foto');
            $table->date('tanggal_sk')->after('nomor_sk');
        });

        Schema::table('permohonans', function (Blueprint $table) {
            $table->string('file_surat')->nullable()->after('catatan');
        });

        // Make changed_by nullable in riwayat_statuses
        Schema::table('riwayat_statuses', function (Blueprint $table) {
            $table->dropForeign(['changed_by']);
        });

        Schema::table('riwayat_statuses', function (Blueprint $table) {
            $table->foreignId('changed_by')->nullable()->change();
        });

        Schema::table('riwayat_statuses', function (Blueprint $table) {
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemohons', function (Blueprint $table) {
            $table->dropColumn(['nomor_sk', 'tanggal_sk']);
        });

        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn('file_surat');
        });

        Schema::table('riwayat_statuses', function (Blueprint $table) {
            $table->dropForeign(['changed_by']);
        });

        Schema::table('riwayat_statuses', function (Blueprint $table) {
            $table->foreignId('changed_by')->nullable(false)->change();
        });

        Schema::table('riwayat_statuses', function (Blueprint $table) {
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
