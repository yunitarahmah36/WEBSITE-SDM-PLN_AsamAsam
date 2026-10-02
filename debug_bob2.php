<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

$auth = service('authentication');
$authorize = service('authorization');

$userId = 2; // Assuming 'andri' or some pembimbing is ID 2. I'll just check all users.

$db = \Config\Database::connect();
$users = $db->table('users')->select('id, username')->get()->getResultArray();
foreach($users as $u) {
    echo $u['username'] . " (ID: " . $u['id'] . ") in_groups('admin,pembimbing'): ";
    
    // Myth/Auth has inGroup($group, $userId)
    // inGroup can take a string or an array.
    $groupArray = ['admin', 'pembimbing'];
    
    $res = false;
    foreach ($groupArray as $group) {
        if ($authorize->inGroup($group, $u['id'])) {
            $res = true;
            break;
        }
    }
    
    echo ($res ? 'TRUE' : 'FALSE') . "\n";
}
