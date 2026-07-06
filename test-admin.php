<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$session = \App\Models\ChatSession::first();
if ($session) {
    \App\Models\ChatMessage::create([
        'chat_session_id' => $session->id,
        'sender' => 'admin',
        'message' => 'Halo dari admin melalui tinker!',
    ]);
    echo "Admin message created.\n";
} else {
    echo "No session found.\n";
}
