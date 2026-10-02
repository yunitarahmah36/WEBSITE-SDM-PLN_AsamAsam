<?php
define('FCPATH', __DIR__ . '/public/');
define('ENVIRONMENT', 'development');
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$db = \Config\Database::connect();
$query = $db->query("SELECT id, user_id, nim, nama, surat_balasan FROM siswa WHERE nama LIKE '%PRASETIO%'");
print_r($query->getResultArray());
