<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuRegistrasiAdvokat extends Model
{
    use HasFactory;

    protected $table = 'buku_registrasi_advokats';

    protected $guarded = ['id'];

    public function pemohon()
    {
        return $this->belongsTo(Pemohon::class, 'pemohon_id');
    }

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }
}
