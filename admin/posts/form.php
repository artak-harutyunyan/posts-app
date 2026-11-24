<?php
require_once '../../includes/functions.php';
require_once '../../includes/db.php';
$admin = require_admin($db);

$sql = 'select p.id, p.user_id, p.title, p.content, p.is_blocked, p.created_at, u.username from posts p join users u on p.user_id = u.id ORDER BY p.created_at DESC';
$result = mysqli_fetch_all(mysqli_query($db, $sql), MYSQLI_ASSOC);

$page_title = 'Manage posts';
require_once __DIR__ . '/../../includes/header.php';
?>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Username</th>
            <th>Title</th>
            <th>Content</th>
            <th>Is Blocked</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post['username']); ?></td>
                <td><?php echo htmlspecialchars($post['title']); ?></td>
                <td><?php echo htmlspecialchars($post['content']); ?></td>
                <td><?php echo $post['is_blocked'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                <td>
                    <form action="/admin/posts/toggle.php" method="post" style="display: inline;">
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                        <input type="hidden" name="is_blocked" value="<?= $post['is_blocked'] ? 0 : 1 ?>">

                        <input type="submit" name="toggle_block"
                            value="<?php echo $post['is_blocked'] ? 'Unblock' : 'Block'; ?>">

                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>