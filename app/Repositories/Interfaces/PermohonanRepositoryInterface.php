<?php

namespace App\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;

interface PermohonanRepositoryInterface extends BaseRepositoryInterface
{
    public function generateNomorRegistrasi();
    public function getWithRelations($id);
    public function updateStatus($id, $status, $catatan, $adminId);
}
