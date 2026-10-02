<?php
$ftp_server = '156.67.222.86';
$ftp_user = 'u535103328';
$ftp_pass = 'g0Ms?uAE274hsd##';

$conn_id = ftp_connect($ftp_server);

if ($conn_id && @ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "FTP SUCCESS\n";
    $files_to_upload = [
        'app/Controllers/Pesan.php',
        'app/Models/PesanModel.php',
        'app/Views/pesan.php',
        'app/Views/main.php',
        'app/Config/Routes.php'
    ];
    
    // Upload files to /public_html/siamam/
    foreach ($files_to_upload as $local_file) {
        $remote_file = '/public_html/siamam/' . $local_file;
        if (ftp_put($conn_id, $remote_file, __DIR__ . '/' . $local_file, FTP_BINARY)) {
            echo "Successfully uploaded $local_file\n";
        } else {
            echo "There was a problem while uploading $local_file\n";
        }
    }
    
    // Also try to upload to /domains/maxinide.com/public_html/siamam/ just in case
    foreach ($files_to_upload as $local_file) {
        $remote_file = '/domains/maxinide.com/public_html/siamam/' . $local_file;
        if (@ftp_put($conn_id, $remote_file, __DIR__ . '/' . $local_file, FTP_BINARY)) {
            echo "Successfully uploaded $local_file to domains/...\n";
        }
    }

    ftp_close($conn_id);
} else {
    echo "FTP FAILED\n";
}
