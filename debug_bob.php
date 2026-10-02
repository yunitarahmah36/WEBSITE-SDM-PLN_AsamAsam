<?php

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$groups = $db->table('auth_groups')->get()->getResultArray();
echo "All groups:\n";
print_r($groups);

$users = $db->table('users')->select('id, username, email')->get(5)->getResultArray();
echo "\nSome users:\n";
foreach($users as $u) {
    $g = $db->table('auth_groups_users')
        ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
        ->where('user_id', $u['id'])
        ->get()->getResultArray();
    echo $u['username'] . " groups: " . implode(',', array_column($g, 'name')) . "\n";
}
