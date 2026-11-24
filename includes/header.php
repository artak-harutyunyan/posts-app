<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ .'/functions.php';

$current_user = current_user($db);
$page_title = $page_title  ?? 'Posts App';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/header.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title><?= htmlspecialchars($page_title) ?></title>
</head>
<body>
    <nav class="nav">
        <ul>
            <li><a href="/main.php">Home</a></li>
            <li><a href="/posts/my/form.php"> My Posts</a></li>
            <li><a href="/posts/create/form.php">Create post</a></li>
            <?php if ($current_user && $current_user['role'] === 'admin'): ?>
                <li><a href="/admin/posts/form.php">Manage Posts</a></li>
                <li><a href="/admin/users/form.php">Manage Users</a></li>
           <?php endif; ?>
            <li>
                <form action="/auth/logout/logout.php" method="POST">
                    <button type="submit">LogOut<?= $current_user ? ' (' . htmlspecialchars($current_user['username']) . ')' : '' ?></button>
                </form>
            </li>
        </ul>
    </nav>
    <div class="content">