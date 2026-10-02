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
$angkatans = $angkatanModel->findAll();

echo "--- All Angkatan ---\n";
foreach ($angkatans as $a) {
    echo "ID: " . $a->id . " | Nama: " . $a->nama . " | Mulai: " . $a->tgl_mulai . " | Selesai: " . $a->tgl_selesai . "\n";
}
echo "--- Current Date ---\n";
echo date('Y-m-d') . "\n";
