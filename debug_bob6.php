<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$pembimbing = $db->table('pembimbing')->get()->getResultArray();
foreach ($pembimbing as $p) {
    $uid = $p['user_id'];
    $u = $db->table('users')->where('id', $uid)->get()->getRow();
    $group = $db->table('auth_groups_users')
        ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
        ->where('user_id', $uid)
        ->get()->getRow();
        
    echo "Pembimbing Name: " . $p['nama'] . " | User ID: $uid | Username: " . ($u->username ?? 'null') . " | Group: " . ($group->name ?? 'NONE') . "\n";
}
