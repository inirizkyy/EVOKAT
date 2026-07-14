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
        // 1. Drop catatan_penolakan column from pemohons table
        if (Schema::hasColumn('pemohons', 'catatan_penolakan')) {
            Schema::table('pemohons', function (Blueprint $table) {
                $table->dropColumn('catatan_penolakan');
            });
        }

        // 2. Insert "Pas Foto Pemohon" into master_persyaratans
        $exists = DB::table('master_persyaratans')->where('nama_persyaratan', 'Pas Foto Pemohon')->exists();
        if (!$exists) {
            DB::table('master_persyaratans')->insert([
                'nama_persyaratan' => 'Pas Foto Pemohon',
                'deskripsi' => 'Pas Foto berwarna terbaru, latar belakang biru/merah, berpakaian rapi dan formal (ukuran maksimal 2MB, format JPG, JPEG, atau PNG).',
                'is_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemohons', function (Blueprint $table) {
            $table->text('catatan_penolakan')->nullable();
        });

        DB::table('master_persyaratans')->where('nama_persyaratan', 'Pas Foto Pemohon')->delete();
    }
};
