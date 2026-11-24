<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$user = require_login($db);
$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false) {
    http_response_code(404);
    exit('Post not found');
}

$stmt = $db->prepare('SELECT id, title, content, image_path FROM posts WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $id, $user['id']);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    http_response_code(404);
    exit('Post not found');
}

$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];

unset($_SESSION['old'], $_SESSION['errors']);  

$title = $old['title'] ?? $post['title'];
$content = $old['content'] ?? $post['content'];

$page_title = 'Edit post';
require_once __DIR__ . '/../../includes/header.php';
?>

<form action="/posts/update/update.php" method="POST" enctype="multipart/form-data">   
    <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">   

    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>
    <?php if (isset($errors['title'])): ?>  
        <p style="color: red;"><?= htmlspecialchars($errors['title']) ?></p>
    <?php endif; ?>

    <label for="content">Content:</label>
    <textarea id="content" name="content" required><?= htmlspecialchars($content) ?></textarea>
    <?php if (isset($errors['content'])): ?>
        <p style="color: red;"><?= htmlspecialchars($errors['content']) ?></p>
    <?php endif; ?>

    <div>
        <?php if (!empty($post['image_path'])): ?>
            <p>Current image:</p>
            <img src="/uploads/<?= htmlspecialchars($post['image_path']) ?>" alt="" style="max-width: 200px;">
        <?php endif; ?>
        <br>
        <label>Change image (optional):</label><br>
        <input type="file" name="image" accept="image/png, image/jpeg, image/webp">
        <?php if (isset($errors['image'])): ?>
            <p style="color: red;"><?= htmlspecialchars($errors['image']) ?></p>
        <?php endif; ?>
    </div>

    <?php if (isset($errors['general'])): ?>
        <p style="color: red;"><?= htmlspecialchars($errors['general']) ?></p>
    <?php endif; ?>

    <button type="submit">Update Post</button>
</form>