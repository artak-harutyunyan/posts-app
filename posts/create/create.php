<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
require_once '../../includes/upload.php';

$user = require_login($db);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit();
}

$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$file = $_FILES['image'] ?? null;
$hasFile = image_was_sent($file);
$ext = null;
$errors = [];

$_SESSION['old'] = [
    'title' => $title,
    'content' => $content
];

if ($title === '') {
    $errors['title'] = 'Title is required';
} elseif (mb_strlen($title, 'UTF-8') > 255) {
    $errors['title'] = 'Title is too long';
}

if ($content === '') {
    $errors['content'] = 'Content is required';
} elseif (mb_strlen($content, 'UTF-8') > 10000) {
    $errors['content'] = 'Content is too long';
}

if ($hasFile) {
    [$ext, $imageError] = check_image($file);
    if ($imageError !== null) {
        $errors['image'] = $imageError;
    }
}

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header('Location: form.php');
    exit();
}

$imageName = null;
if ($hasFile) {
    $imageName = save_image($file, $ext);
    if ($imageName === null) {
        $_SESSION['errors'] = ['image' => 'Could not save the image'];
        header('Location: form.php');
        exit();
    }
}

$stmt = $db->prepare('INSERT INTO posts (title, content, user_id, image_path) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssis', $title, $content, $user['id'], $imageName);

if ($stmt->execute()) {
    unset($_SESSION['old']);
    header('Location: /posts/my/form.php');
    exit();
}

delete_image($imageName);
$_SESSION['errors'] = ['general' => 'Could not create the post'];
header('Location: form.php');
exit();
