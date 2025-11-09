<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "Fonnte API Key: " . env('FONNTE_API_KEY') . PHP_EOL;
echo "Cloudinary URL: " . env('CLOUDINARY_URL') . PHP_EOL;
