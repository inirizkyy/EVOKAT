<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisasiAdvokat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function pemohons()
    {
        return $this->hasMany(Pemohon::class, 'organisasi_id');
    }
}
