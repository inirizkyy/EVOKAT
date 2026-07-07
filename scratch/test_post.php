<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/tracking', 'POST', [
    'nomor_permohonan' => 'ADV-20260703-0001'
]);

// Add CSRF token or mock validation
$response = $app->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
if ($response instanceof Illuminate\Http\RedirectResponse) {
    echo "Redirect target: " . $response->getTargetUrl() . "\n";
    echo "Session errors: " . print_r(session('errors'), true) . "\n";
    echo "Session error msg: " . session('error') . "\n";
} else {
    $content = $response->getContent();
    echo "Content contains 'Detail Permohonan': " . (str_contains($content, 'Detail Permohonan') ? 'YES' : 'NO') . "\n";
    echo "Content contains 'Nomor Registrasi tidak ditemukan': " . (str_contains($content, 'tidak ditemukan') ? 'YES' : 'NO') . "\n";
}
