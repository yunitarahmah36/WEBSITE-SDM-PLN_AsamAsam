<?php
require 'vendor/autoload.php';
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();
$app = Config\Services::codeigniter();
$app->initialize();

$angkatanModel = new \App\Models\AngkatanModel();
$data = [
    'tgl_mulai' => '2026-01-01',
    'tgl_selesai' => '2026-12-31'
];

if ($angkatanModel->update(2, $data)) {
    echo "Angkatan ID 2 updated successfully to 2026.\n";
} else {
    echo "Failed to update Angkatan ID 2.\n";
}
