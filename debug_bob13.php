<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

$auth = service('authentication');
$success = $auth->attempt(['username' => 'andri', 'password' => 'pembimbinglapangan'], false);

if (!$success) {
    echo "Login failed!\n";
    exit;
}

echo "Login success. User ID: " . $auth->id() . "\n";
echo "Groups: ";
print_r(service('authorization')->getGroupMode()); // Check groups
print_r(service('authorization')->getGroupsForUser($auth->id())); // wait, groupModel has getGroupsForUser

// Let's manually trigger the controller logic!
$uid = $auth->id();
$db = \Config\Database::connect();
echo "PID: " . getPidByUid($uid) . "\n";

$tempat_ids_query = $db->table('tempat_magang')
    ->select('id')
    ->where('pid', getPidByUid($uid))
    ->get()
    ->getResultArray();
    
echo "Tempat IDs: ";
print_r($tempat_ids_query);

$tempat_ids = array_column($tempat_ids_query, 'id');
$siswa_ids_query = $db->table('lamaran')
    ->select('id_siswa')
    ->whereIn('id_tempat', empty($tempat_ids) ? [-1] : $tempat_ids)
    ->whereIn('status', ['accepted', 'selesai'])
    ->get()
    ->getResultArray();
    
echo "Siswa IDs: ";
print_r($siswa_ids_query);
