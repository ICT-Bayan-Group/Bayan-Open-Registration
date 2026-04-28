<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$response = \Illuminate\Support\Facades\Http::withToken('7b0234b45d85a6400b19d1aaa93f6927')
    ->acceptJson()
    ->post('https://omnichannel.qiscus.com/api/v1/send_message', [
        'to'            => '6285377640809',
        'type'          => 'template',
        'template_name' => 'payment_link_bayanopen',
        'language'      => 'id',
        'parameters'    => ['Nama Test', 'Tim Test', 'Ganda Dewasa', 'Rp 400.000', 'https://google.com', '01 Jan 2026'],
    ]);

echo "STATUS: " . $response->status() . "\n";
echo "BODY: " . $response->body() . "\n";