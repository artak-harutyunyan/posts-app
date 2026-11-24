<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';


$user = require_login($db);

$sql = 'SELECT p.id, p.user_id, p.title, p.content, p.created_at, p.image_path, u.username
        FROM posts p
        JOIN users u ON u.id = p.user_id
        WHERE p.is_blocked = 0 AND u.is_blocked = 0
        ORDER BY p.created_at DESC
        LIMIT 20';
$posts = $db->query($sql)->fetch_all(MYSQLI_ASSOC);