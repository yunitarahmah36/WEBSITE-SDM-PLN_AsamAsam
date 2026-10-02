<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

$filters = Config\Services::filters();
$filters->initialize();
// We know Routes.php has: $routes->get('/man/siswa', 'Home::man_siswa', ['filter' => 'role:admin,pembimbing']);
// Let's manually get arguments for the role filter on a mocked route string if possible.
// Actually, let's just parse it the way CI4 does:
$routeFilter = 'role:admin,pembimbing';
$clean = str_replace('role:', '', $routeFilter);
$args = explode(',', $clean);
print_r($args);
