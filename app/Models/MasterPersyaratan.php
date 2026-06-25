<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPersyaratan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function dokumenPersyaratan()
    {
        return $this->hasMany(DokumenPersyaratan::class, 'persyaratan_id');
    }
}
