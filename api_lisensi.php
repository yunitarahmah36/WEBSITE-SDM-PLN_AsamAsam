<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// UBAH NILAI INI
// 'aktif' -> Sistem berjalan normal
// 'kunci' -> Sistem diblokir dari jarak jauh
$status = 'kunci';

echo json_encode(['status' => $status]);
?>
    