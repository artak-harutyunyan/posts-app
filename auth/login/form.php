<?php
session_start();
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? [];
unset($_SESSION['old']);
unset($_SESSION['error']);

$page_title = 'Login';
require_once __DIR__ . '/../../includes/auth_header.php';
?>

<form action="/auth/login/login.php" method="POST">
    <h3>Login</h3>
    <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
    <?php if (isset($error['username'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['username']) ?></p>
    <?php endif; ?>
    <br>
    <input type="password" name="password" placeholder="Password">
    <?php if (isset($error['password'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['password']) ?></p>
    <?php endif; ?>
    <br>
    <?php if (isset($error['form'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['form']) ?></p>
    <?php endif; ?>
    <input type="submit" value="Login">
    <br>
    <a class="auth-link" href="/auth/register/form.php">Create an account</a>
</form>

<?php require_once __DIR__ . '/../../includes/auth_footer.php'; ?>
