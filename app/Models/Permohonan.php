<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::created(function ($permohonan) {
            \App\Models\RiwayatStatus::create([
                'permohonan_id' => $permohonan->id,
                'status_lama' => 'Baru',
                'status_baru' => $permohonan->status,
                'keterangan' => 'Permohonan berhasil diajukan.',
                'changed_by' => auth()->id(),
            ]);
        });

        static::updated(function ($permohonan) {
            if ($permohonan->wasChanged('status')) {
                \App\Models\RiwayatStatus::create([
                    'permohonan_id' => $permohonan->id,
                    'status_lama' => $permohonan->getOriginal('status'),
                    'status_baru' => $permohonan->status,
                    'keterangan' => $permohonan->catatan ?? 'Status diperbarui.',
                    'changed_by' => auth()->id(),
                ]);
            }
        });
    }

    public function pemohon()
    {
        return $this->belongsTo(Pemohon::class, 'pemohon_id');
    }

    public function dokumenPersyaratan()
    {
        return $this->hasMany(DokumenPersyaratan::class, 'permohonan_id');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'permohonan_id');
    }

    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatus::class, 'permohonan_id');
    }

    public function jadwalSumpah()
    {
        return $this->hasOne(JadwalSumpah::class, 'permohonan_id');
    }

    public function bukuRegistrasi()
    {
        return $this->hasOne(BukuRegistrasiAdvokat::class, 'permohonan_id');
    }
}
