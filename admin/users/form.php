<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';


$user = require_admin($db);

$sql = 'select id, username, email, role, is_blocked from users';
$result = mysqli_fetch_all(mysqli_query($db, $sql), MYSQLI_ASSOC);

$page_title = 'Manage users';
require_once __DIR__ . '/../../includes/header.php';
?>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Is Blocked</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
                <td><?php echo $u['is_blocked'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <form action="/admin/users/toggle.php" method="post" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($u['id']); ?>">
                        <input type="hidden" name="is_blocked" value="<?= $u['is_blocked'] ? 0 : 1 ?>">
                        <?php if ($user['id'] == $u['id']): ?>
                            <input type="button" name="toggle_block" value="<?php echo 'You'; ?>">
                        <?php else: ?>
                            <input type="submit" name="toggle_block"
                                value="<?php echo $u['is_blocked'] ? 'Unblock' : 'Block'; ?>">
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>