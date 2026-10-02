<?php
$ftp_server = '156.67.222.86';
$ftp_user = 'u535103328';
$ftp_pass = 'g0Ms?uAE274hsd##';

$conn_id = ftp_connect($ftp_server);

if ($conn_id && @ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "FTP SUCCESS\n";
    ftp_pasv($conn_id, true);
    
    $base_dir = '/domains/siamam.maxinide.com/public_html/';
    
    // Upload the files we changed
    $files_to_upload = [
        'app/Views/profile.php',
        'deploy_patch/app/Views/profile.php',
        'deploy_patch/app/Views/man_siswa.php',
        'public/assets/img/default-avatar.svg'
    ];
    
    foreach ($files_to_upload as $local_file) {
        $remote_file = $base_dir . $local_file;
        if (@ftp_put($conn_id, $remote_file, __DIR__ . '/' . $local_file, FTP_BINARY)) {
            echo "Successfully uploaded $local_file\n";
        } else {
            echo "Failed to upload $local_file\n";
        }
    }
    
    // Delete the old default image
    $old_image = $base_dir . 'public/assets/img/bruce-mars.jpg';
    if (@ftp_delete($conn_id, $old_image)) {
        echo "Successfully deleted $old_image\n";
    } else {
        echo "Failed to delete $old_image (might not exist)\n";
    }

    ftp_close($conn_id);
} else {
    echo "FTP FAILED\n";
}
