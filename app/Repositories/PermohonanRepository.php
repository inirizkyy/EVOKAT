<?php

namespace App\Repositories;

use App\Models\Permohonan;
use App\Models\RiwayatStatus;
use App\Models\Verifikasi;
use App\Repositories\Interfaces\PermohonanRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PermohonanRepository extends BaseRepository implements PermohonanRepositoryInterface
{
    public function __construct(Permohonan $model)
    {
        parent::__construct($model);
    }

    public function generateNomorRegistrasi()
    {
        $prefix = 'ADV-' . Carbon::now()->format('Ymd') . '-';
        $lastPermohonan = $this->model->where('nomor_permohonan', 'like', $prefix . '%')
            ->orderBy('nomor_permohonan', 'desc')
            ->first();

        if (!$lastPermohonan) {
            return $prefix . '0001';
        }

        $lastNumber = intval(substr($lastPermohonan->nomor_permohonan, -4));
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getWithRelations($id)
    {
        return $this->model->with([
            'pemohon.organisasi',
            'pemohons.organisasi',
            'pemohons.dokumenPersyaratan.masterPersyaratan',
            'dokumenPersyaratan.masterPersyaratan',
            'riwayatStatus',
            'jadwalSumpah',
            'verifikasi',
            'organisasi'
        ])->find($id);
    }

    public function updateStatus($id, $status, $catatan, $adminId)
    {
        DB::beginTransaction();
        try {
            $permohonan = $this->find($id);
            $statusLama = $permohonan->status;

            // Update Permohonan
            $permohonan->update([
                'status' => $status,
                'catatan' => $catatan
            ]);

            // Catat Riwayat Status
            RiwayatStatus::create([
                'permohonan_id' => $id,
                'status_lama' => $statusLama,
                'status_baru' => $status,
                'keterangan' => $catatan,
                'changed_by' => $adminId,
            ]);

            // Jika status verifikasi (misal: Disetujui, Ditolak, Berkas Kurang)
            Verifikasi::create([
                'permohonan_id' => $id,
                'admin_id' => $adminId,
                'status_verifikasi' => $status,
                'catatan' => $catatan
            ]);

            DB::commit();
            return $permohonan;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
