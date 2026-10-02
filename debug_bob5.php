<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$pembimbing_users = $db->table('auth_groups_users')
    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
    ->where('auth_groups.name', 'pembimbing')
    ->get()->getResultArray();

foreach ($pembimbing_users as $pu) {
    $uid = $pu['user_id'];
    $data = $db->table('pembimbing')->where('user_id', $uid)->get()->getRow();
    echo "User ID $uid: ";
    if ($data) {
        echo "Found PID " . $data->id . "\n";
    } else {
        echo "MISSING IN pembimbing TABLE!\n";
    }
}
