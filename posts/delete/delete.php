<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
require_once '../../includes/upload.php';

$user = require_login($db);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /posts/my/form.php');
    exit();
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false) {
    header('Location: /posts/my/form.php');
    exit();
}

// Read the image name first, otherwise we cannot delete the file afterwards
$stmt = $db->prepare('SELECT image_path FROM posts WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $id, $user['id']);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if ($post) {
    $stmt = $db->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $id, $user['id']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        delete_image($post['image_path']);
    }
}

unset($_SESSION['old']);
header('Location: /posts/my/form.php');
exit();
