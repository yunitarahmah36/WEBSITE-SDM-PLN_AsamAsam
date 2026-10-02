<?php
require 'public/index.php';
$db = \Config\Database::connect();
// Let's assume we don't know the user_id, we just want to see if there are any unread messages with missing senders
$sql = "SELECT p.receiver_id, p.sender_id, count(*) as c 
        FROM pesan p 
        LEFT JOIN users u ON p.sender_id = u.id 
        WHERE p.is_read = 0 AND u.id IS NULL 
        GROUP BY p.receiver_id, p.sender_id";
$res = $db->query($sql)->getResult();
print_r($res);
