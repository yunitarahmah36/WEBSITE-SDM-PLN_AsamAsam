<?php
$logDir = __DIR__ . '/writable/logs/';
$files = glob($logDir . '*.log');
rsort($files); // Get latest first

foreach ($files as $file) {
    echo "--- FILE: " . basename($file) . " ---\n";
    $lines = file($file);
    foreach ($lines as $line) {
        if (stripos($line, 'CRITICAL') !== false || stripos($line, 'ERROR') !== false || stripos($line, 'Exception') !== false) {
            echo $line;
        }
    }
    echo "\n\n";
    break; // Just the latest day
}
