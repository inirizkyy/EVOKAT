<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemohon extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function organisasi()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function dokumenPersyaratan()
    {
        return $this->hasMany(DokumenPersyaratan::class, 'pemohon_id');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'pemohon_id');
    }

    public function bukuRegistrasi()
    {
        return $this->hasOne(BukuRegistrasiAdvokat::class, 'pemohon_id');
    }

    public function getFotoAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Cache the master requirement ID static in-memory for performance
        static $pasFotoReqId = null;
        if ($pasFotoReqId === null) {
            $pasFotoReq = \App\Models\MasterPersyaratan::where('nama_persyaratan', 'like', '%pas foto%')->first();
            $pasFotoReqId = $pasFotoReq ? $pasFotoReq->id : false;
        }

        if ($pasFotoReqId) {
            if ($this->relationLoaded('dokumenPersyaratan')) {
                $doc = $this->dokumenPersyaratan->firstWhere('persyaratan_id', $pasFotoReqId);
            } else {
                $doc = $this->dokumenPersyaratan()->where('persyaratan_id', $pasFotoReqId)->first();
            }
            
            if ($doc) {
                return $doc->file_path;
            }
        }

        return null;
    }
}
