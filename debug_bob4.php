<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

// Initialize session
$session = \Config\Services::session();
$session->start();

// Mock auth
$auth = service('authentication');
$auth->loginById(72); // Login as 'andri' (pembimbing)
echo "Logged in as: " . user()->username . "\n";

try {
    $home = new \App\Controllers\Home();
    $home->initController(
        \Config\Services::request(),
        \Config\Services::response(),
        \Config\Services::logger()
    );
    
    echo "Running man_siswa()...\n";
    ob_start();
    $result = $home->man_siswa();
    ob_end_clean();
    echo "man_siswa() ran successfully.\n";

    echo "Running application()...\n";
    ob_start();
    $result = $home->application();
    ob_end_clean();
    echo "application() ran successfully.\n";

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
