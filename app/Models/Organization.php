<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = ['id'];

    public function pemohons()
    {
        return $this->hasMany(Pemohon::class, 'organization_id');
    }

    public function permohonans()
    {
        return $this->hasMany(Permohonan::class, 'organization_id');
    }
}
