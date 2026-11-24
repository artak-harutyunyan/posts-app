<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$user = require_login($db);

$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];

unset($_SESSION['old']);
unset($_SESSION['errors']);

$page_title = 'Create post';
require_once __DIR__ . '/../../includes/header.php';



?>

<form action="/posts/create/create.php" method="POST" class="post" enctype="multipart/form-data">
    <div>
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">
        <?php if (isset($errors['title'])): ?>
            <p style="color: red;"><?= htmlspecialchars($errors['title']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label>Content:</label><br>
        <textarea name="content"><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
        <?php if (isset($errors['content'])): ?>
            <p style="color: red;"><?= htmlspecialchars($errors['content']) ?></p>
        <?php endif; ?>
    </div>
     <div>
       <label>Image (optional):</label><br>
    <input type="file" name="image" accept="image/png, image/jpeg, image/webp">
    <?php if (isset($errors['image'])): ?>
        <p style="color: red;"><?= htmlspecialchars($errors['image']) ?></p>
    <?php endif; ?>
     </div>
    <?php if (isset($errors['general'])): ?>
        <p style="color: red;"><?= htmlspecialchars($errors['general']) ?></p>
    <?php endif; ?>

    <button type="submit">Create Post</button>
</form>