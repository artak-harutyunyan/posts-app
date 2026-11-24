<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$user = require_login($db);


$stmt = $db->prepare('SELECT id, title, content, created_at, image_path, is_blocked FROM posts WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
