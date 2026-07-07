<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$permohonan = App\Models\Permohonan::first();
if ($permohonan) {
    echo "Nomor Permohonan: " . $permohonan->nomor_permohonan . "\n";
    echo "Status: " . $permohonan->status . "\n";
} else {
    echo "No permohonan found in database.\n";
}
