<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom baru ke permohonans
        Schema::table('permohonans', function (Blueprint $table) {
            $table->foreignId('organisasi_id')->nullable()->after('pemohon_id')->constrained('organisasi_advokats')->onDelete('cascade');
            $table->string('nomor_sk')->nullable()->after('organisasi_id');
            $table->date('tanggal_sk')->nullable()->after('nomor_sk');
            $table->string('no_hp_organisasi')->nullable()->after('tanggal_sk');
            $table->string('email_organisasi')->nullable()->after('no_hp_organisasi');
        });

        // 2. Tambah kolom baru ke pemohons
        Schema::table('pemohons', function (Blueprint $table) {
            $table->foreignId('permohonan_id')->nullable()->after('id')->constrained('permohonans')->onDelete('cascade');
            $table->string('status_verifikasi')->default('Pending')->after('tanggal_sk');
            $table->text('catatan_penolakan')->nullable()->after('status_verifikasi');
        });

        // 3. Tambah kolom baru ke dokumen_persyaratans
        Schema::table('dokumen_persyaratans', function (Blueprint $table) {
            $table->foreignId('pemohon_id')->nullable()->after('permohonan_id')->constrained('pemohons')->onDelete('cascade');
        });

        // 4. Tambah kolom baru ke verifikasis
        Schema::table('verifikasis', function (Blueprint $table) {
            $table->foreignId('pemohon_id')->nullable()->after('permohonan_id')->constrained('pemohons')->onDelete('cascade');
        });

        // 5. Migrasi data lama secara aman
        // Hubungkan pemohon dengan permohonan
        DB::statement("UPDATE pemohons SET permohonan_id = (SELECT id FROM permohonans WHERE permohonans.pemohon_id = pemohons.id)");
        
        // Hubungkan permohonan dengan organisasi dan SK
        DB::statement("UPDATE permohonans SET 
            organisasi_id = (SELECT organisasi_id FROM pemohons WHERE pemohons.id = permohonans.pemohon_id),
            nomor_sk = (SELECT nomor_sk FROM pemohons WHERE pemohons.id = permohonans.pemohon_id),
            tanggal_sk = (SELECT tanggal_sk FROM pemohons WHERE pemohons.id = permohonans.pemohon_id),
            no_hp_organisasi = (SELECT no_hp FROM pemohons WHERE pemohons.id = permohonans.pemohon_id),
            email_organisasi = (SELECT email FROM pemohons WHERE pemohons.id = permohonans.pemohon_id)
        ");

        // Set status verifikasi anggota lama berdasarkan status permohonan
        DB::statement("UPDATE pemohons SET status_verifikasi = (
            SELECT CASE 
                WHEN status IN ('Disetujui', 'Selesai', 'Dijadwalkan Sumpah') THEN 'Disetujui' 
                WHEN status = 'Ditolak' THEN 'Ditolak' 
                ELSE 'Pending' 
            END FROM permohonans WHERE permohonans.id = pemohons.permohonan_id
        )");

        // Hubungkan dokumen dengan pemohon
        DB::statement("UPDATE dokumen_persyaratans SET pemohon_id = (SELECT pemohon_id FROM permohonans WHERE permohonans.id = dokumen_persyaratans.permohonan_id)");

        // Hubungkan verifikasi dengan pemohon
        DB::statement("UPDATE verifikasis SET pemohon_id = (SELECT pemohon_id FROM permohonans WHERE permohonans.id = verifikasis.permohonan_id)");

        // 6. Ubah kolom tabel pemohons dan permohonans menjadi nullable agar aman
        Schema::table('pemohons', function (Blueprint $table) {
            $table->foreignId('organisasi_id')->nullable()->change();
            $table->string('nik', 16)->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->string('no_hp')->nullable()->change();
            $table->string('nomor_sk')->nullable()->change();
            $table->date('tanggal_sk')->nullable()->change();
        });

        Schema::table('permohonans', function (Blueprint $table) {
            $table->foreignId('pemohon_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->foreignId('pemohon_id')->nullable(false)->change();
            $table->dropForeign(['organisasi_id']);
            $table->dropColumn(['organisasi_id', 'nomor_sk', 'tanggal_sk', 'no_hp_organisasi', 'email_organisasi']);
        });

        Schema::table('pemohons', function (Blueprint $table) {
            $table->foreignId('organisasi_id')->nullable(false)->change();
            $table->string('nik', 16)->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
            $table->string('no_hp')->nullable(false)->change();
            $table->string('nomor_sk')->nullable(false)->change();
            $table->date('tanggal_sk')->nullable(false)->change();
            $table->dropForeign(['permohonan_id']);
            $table->dropColumn(['permohonan_id', 'status_verifikasi', 'catatan_penolakan']);
        });

        Schema::table('dokumen_persyaratans', function (Blueprint $table) {
            $table->dropForeign(['pemohon_id']);
            $table->dropColumn('pemohon_id');
        });

        Schema::table('verifikasis', function (Blueprint $table) {
            $table->dropForeign(['pemohon_id']);
            $table->dropColumn('pemohon_id');
        });
    }
};
