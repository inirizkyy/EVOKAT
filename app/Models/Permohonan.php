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

    public function pemohons()
    {
        return $this->hasMany(Pemohon::class, 'permohonan_id');
    }

    public function organisasi()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
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

    public function syncStatusAndNotify()
    {
        $hasJadwal = $this->jadwalSumpah()->exists() &&
                     !empty($this->jadwalSumpah->tanggal) &&
                     !empty($this->jadwalSumpah->jam);

        $hasFinalSurat = !empty($this->file_surat) &&
                         \Illuminate\Support\Str::contains($this->file_surat, 'surat_final_') &&
                         \Illuminate\Support\Facades\Storage::disk('public')->exists($this->file_surat);

        if ($hasJadwal && $hasFinalSurat) {
            if ($this->status !== 'Dijadwalkan Sumpah' && $this->status !== 'Selesai' && $this->status !== 'Ditolak') {
                $this->status = 'Dijadwalkan Sumpah';
                $this->save();

                try {
                    $jadwal = $this->jadwalSumpah;
                    \Illuminate\Support\Facades\Mail::to($this->email_organisasi, $this->organisasi->nama_organisasi ?? 'Organisasi')
                        ->send(new \App\Mail\JadwalSumpahMail($jadwal));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal mengirim email jadwal sumpah dari syncStatusAndNotify: ' . $e->getMessage());
                }
                return true;
            }
        }
        return false;
    }
}
