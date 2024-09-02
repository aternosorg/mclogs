<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$config = \Config::Get('storage');

echo json_encode([
    'storageTime' => $config['storageTime'],
    'maxLength' => $config['maxLength'],
    'maxLines' => $config['maxLines']
]);

