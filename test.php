<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$session = \App\Models\ChatSession::first();
echo "Session ID: " . $session->id . "\n";
echo "Session UUID: " . $session->uuid . "\n";
$msgs = $session->messages;
echo "Messages Count: " . $msgs->count() . "\n";
foreach($msgs as $msg) {
    echo "Sender: " . $msg->sender . " - " . $msg->message . "\n";
}
