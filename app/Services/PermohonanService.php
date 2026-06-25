<?php

namespace App\Services;

use App\Models\Pemohon;
use App\Models\DokumenPersyaratan;
use App\Repositories\Interfaces\PermohonanRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PermohonanService
{
    protected $permohonanRepo;

    public function __construct(PermohonanRepositoryInterface $permohonanRepo)
    {
        $this->permohonanRepo = $permohonanRepo;
    }

    public function submitPermohonan($pemohonData, $dokumenData)
    {
        DB::beginTransaction();
        try {
            // Upload Foto Pemohon
            if (isset($pemohonData['foto'])) {
                $fotoPath = $pemohonData['foto']->store('pemohon/foto', 'public');
                $pemohonData['foto'] = $fotoPath;
            }

            $pemohon = Pemohon::create($pemohonData);

            $nomorPermohonan = $this->permohonanRepo->generateNomorRegistrasi();

            $permohonan = $this->permohonanRepo->create([
                'pemohon_id' => $pemohon->id,
                'nomor_permohonan' => $nomorPermohonan,
                'tanggal_pengajuan' => date('Y-m-d'),
                'status' => 'Menunggu Verifikasi',
            ]);

            // Upload dan Simpan Dokumen
            foreach ($dokumenData as $persyaratanId => $file) {
                $path = $file->store('permohonan/dokumen/' . $nomorPermohonan, 'public');
                DokumenPersyaratan::create([
                    'permohonan_id' => $permohonan->id,
                    'persyaratan_id' => $persyaratanId,
                    'file_path' => $path,
                    'status_dokumen' => 'Pending'
                ]);
            }

            DB::commit();
            return $permohonan;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getPermohonanByNomor($nomor)
    {
        $permohonan = \App\Models\Permohonan::where('nomor_permohonan', $nomor)->first();
        if ($permohonan) {
            return $this->permohonanRepo->getWithRelations($permohonan->id);
        }
        return null;
    }
}
