<?php
require_once '../config/app.php';
// Cache sederhana pakai file tmp (tidak pakai session karena Vercel serverless)
header('Content-Type: application/json');

$type = $_GET['type'] ?? 'provinces';
$id   = $_GET['id']   ?? '';

$cacheDir  = sys_get_temp_dir();
$cacheKey  = 'bps_' . md5($type . $id) . '.json';
$cachePath = $cacheDir . '/' . $cacheKey;

// Cek cache file (berlaku 1 jam)
if (file_exists($cachePath) && (time() - filemtime($cachePath)) < 3600) {
    echo file_get_contents($cachePath);
    exit;
}

if ($type === 'provinces') {
    $url = 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json';
} elseif ($type === 'cities' && $id) {
    $url = "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$id}.json";
} else {
    echo json_encode([]);
    exit;
}

$ctx = stream_context_create(['http' => ['timeout' => 8]]);
$raw = @file_get_contents($url, false, $ctx);

if ($raw === false) {
    echo json_encode(['error' => 'Gagal mengambil data wilayah']);
    exit;
}

// Simpan cache
@file_put_contents($cachePath, $raw);
echo $raw;
