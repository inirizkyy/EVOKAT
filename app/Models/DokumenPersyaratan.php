<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPersyaratan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function masterPersyaratan()
    {
        return $this->belongsTo(MasterPersyaratan::class, 'persyaratan_id');
    }
}
