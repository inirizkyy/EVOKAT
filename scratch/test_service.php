<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = $app->make(App\Services\PermohonanService::class);
$permohonan = $service->getPermohonanByNomor('ADV-20260703-0001');

if ($permohonan) {
    echo "Found permohonan: " . $permohonan->nomor_permohonan . "\n";
    echo "Status: " . $permohonan->status . "\n";
    echo "Pemohon Name: " . ($permohonan->pemohon ? $permohonan->pemohon->nama_lengkap : 'NULL') . "\n";
    echo "Organisasi: " . ($permohonan->pemohon && $permohonan->pemohon->organisasi ? $permohonan->pemohon->organisasi->nama_organisasi : 'NULL') . "\n";
    echo "Jadwal Sumpah: " . ($permohonan->jadwalSumpah ? 'YES' : 'NO') . "\n";
    echo "Riwayat Status Count: " . $permohonan->riwayatStatus->count() . "\n";
} else {
    echo "Permohonan not found via Service.\n";
}
