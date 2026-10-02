<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$user = $db->table('users')->where('username', 'andri')->get()->getRowArray();
if (!$user) {
    echo "User not found\n";
    exit;
}
echo "User ID: " . $user['id'] . "\n";
$groups = $db->table('auth_groups_users')
             ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
             ->where('user_id', $user['id'])
             ->get()->getResultArray();
echo "Groups:\n";
print_r($groups);
