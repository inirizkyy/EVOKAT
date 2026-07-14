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
        // 1. Rename organisasi_advokats to organizations if it exists
        if (Schema::hasTable('organisasi_advokats') && !Schema::hasTable('organizations')) {
            Schema::rename('organisasi_advokats', 'organizations');
        }

        // 2. Add columns to organizations
        if (Schema::hasTable('organizations')) {
            Schema::table('organizations', function (Blueprint $table) {
                if (!Schema::hasColumn('organizations', 'singkatan')) {
                    $table->string('singkatan')->nullable()->after('nama_organisasi');
                }
                if (!Schema::hasColumn('organizations', 'status')) {
                    $table->string('status')->default('Aktif')->after('singkatan');
                }
                if (!Schema::hasColumn('organizations', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // 3. Update pemohons table: rename organisasi_id to organization_id
        if (Schema::hasTable('pemohons')) {
            Schema::table('pemohons', function (Blueprint $table) {
                // Drop foreign key if exists
                try {
                    $table->dropForeign(['organisasi_id']);
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
                
                if (Schema::hasColumn('pemohons', 'organisasi_id') && !Schema::hasColumn('pemohons', 'organization_id')) {
                    $table->renameColumn('organisasi_id', 'organization_id');
                }
            });
            
            Schema::table('pemohons', function (Blueprint $table) {
                if (Schema::hasColumn('pemohons', 'organization_id')) {
                    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
                }
            });
        }

        // 4. Update permohonans table: rename organisasi_id to organization_id
        if (Schema::hasTable('permohonans')) {
            Schema::table('permohonans', function (Blueprint $table) {
                // Drop foreign key if exists
                try {
                    $table->dropForeign(['organisasi_id']);
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
                
                if (Schema::hasColumn('permohonans', 'organisasi_id') && !Schema::hasColumn('permohonans', 'organization_id')) {
                    $table->renameColumn('organisasi_id', 'organization_id');
                }
            });
            
            Schema::table('permohonans', function (Blueprint $table) {
                if (Schema::hasColumn('permohonans', 'organization_id')) {
                    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
                }
            });
        }

        // 5. Merge duplicates case-insensitively to clean up the DB
        $orgs = DB::table('organizations')->get();
        $processed = [];
        foreach ($orgs as $org) {
            $nameLower = strtolower(trim($org->nama_organisasi));
            if (isset($processed[$nameLower])) {
                $masterId = $processed[$nameLower];
                // Update pemohons and permohonans
                DB::table('pemohons')->where('organization_id', $org->id)->update(['organization_id' => $masterId]);
                DB::table('permohonans')->where('organization_id', $org->id)->update(['organization_id' => $masterId]);
                // Delete duplicate
                DB::table('organizations')->where('id', $org->id)->delete();
            } else {
                $processed[$nameLower] = $org->id;
                $singkatan = $org->singkatan;
                if (!$singkatan && strlen($org->nama_organisasi) <= 10) {
                    $singkatan = strtoupper($org->nama_organisasi);
                }
                DB::table('organizations')->where('id', $org->id)->update([
                    'nama_organisasi' => trim($org->nama_organisasi),
                    'singkatan' => $singkatan,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pemohons')) {
            Schema::table('pemohons', function (Blueprint $table) {
                try {
                    $table->dropForeign(['organization_id']);
                } catch (\Exception $e) {}
                
                if (Schema::hasColumn('pemohons', 'organization_id') && !Schema::hasColumn('pemohons', 'organisasi_id')) {
                    $table->renameColumn('organization_id', 'organisasi_id');
                }
            });
        }

        if (Schema::hasTable('permohonans')) {
            Schema::table('permohonans', function (Blueprint $table) {
                try {
                    $table->dropForeign(['organization_id']);
                } catch (\Exception $e) {}
                
                if (Schema::hasColumn('permohonans', 'organization_id') && !Schema::hasColumn('permohonans', 'organisasi_id')) {
                    $table->renameColumn('organization_id', 'organisasi_id');
                }
            });
        }

        if (Schema::hasTable('organizations')) {
            Schema::table('organizations', function (Blueprint $table) {
                if (Schema::hasColumn('organizations', 'singkatan')) {
                    $table->dropColumn('singkatan');
                }
                if (Schema::hasColumn('organizations', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('organizations', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });

            Schema::rename('organizations', 'organisasi_advokats');
        }

        if (Schema::hasTable('pemohons') && Schema::hasTable('organisasi_advokats')) {
            Schema::table('pemohons', function (Blueprint $table) {
                $table->foreign('organisasi_id')->references('id')->on('organisasi_advokats')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('permohonans') && Schema::hasTable('organisasi_advokats')) {
            Schema::table('permohonans', function (Blueprint $table) {
                $table->foreign('organisasi_id')->references('id')->on('organisasi_advokats')->onDelete('cascade');
            });
        }
    }
};
