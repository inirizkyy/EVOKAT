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
        return $this->belongsTo(OrganisasiAdvokat::class, 'organisasi_id');
    }

    public function permohonan()
    {
        return $this->hasOne(Permohonan::class, 'pemohon_id');
    }
}
