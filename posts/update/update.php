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

// The current post (also the ownership check: someone else's post is not found)
$stmt = $db->prepare('SELECT image_path FROM posts WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $id, $user['id']);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();

if (!$current) {
    header('Location: /posts/my/form.php');
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
    header('Location: /posts/update/form.php?id=' . urlencode((string) $id));
    exit();
}

// Save the new image (if one was chosen)
$newImage = null;
if ($hasFile) {
    $newImage = save_image($file, $ext);
    if ($newImage === null) {
        $_SESSION['errors'] = ['image' => 'Could not save the image'];
        header('Location: /posts/update/form.php?id=' . urlencode((string) $id));
        exit();
    }
}

// New image if one was uploaded, otherwise keep the old one
$imageName = $newImage ?? $current['image_path'];

$stmt = $db->prepare('UPDATE posts SET title = ?, content = ?, image_path = ? WHERE id = ? AND user_id = ?');
$stmt->bind_param('sssii', $title, $content, $imageName, $id, $user['id']);

if ($stmt->execute()) {
    // The update worked: now it is safe to delete the old file
    if ($newImage !== null) {
        delete_image($current['image_path']);
    }
    unset($_SESSION['old']);
    header('Location: /posts/my/form.php');
    exit();
}

// UPDATE failed: remove the new file we just saved
delete_image($newImage);
$_SESSION['errors'] = ['general' => 'Could not update the post'];
header('Location: /posts/update/form.php?id=' . urlencode((string) $id));
exit();
