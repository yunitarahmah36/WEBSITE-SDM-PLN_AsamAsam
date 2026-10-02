<?php
define('FCPATH', __DIR__ . '/public/');
chdir(__DIR__);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

$app = Config\Services::codeigniter();
$app->initialize();

// Try to send email
$email = \Config\Services::email();
$email->setFrom('siamam@maxinide.com', 'Sistem Informasi Magang');
$email->setTo('adminplnasame@gmail.com');
$email->setSubject('Test Email SIAMAM');
$email->setMessage('This is a test email to verify mail delivery.');

if ($email->send()) {
    echo "SUCCESS: Email sent successfully!\n";
} else {
    echo "FAILED: Email could not be sent.\n";
    echo $email->printDebugger(['headers', 'subject', 'body']);
}
