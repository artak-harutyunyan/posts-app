<?php

session_start();
$old = $_SESSION['old'] ?? [];
$error = $_SESSION['error'] ?? [];
unset($_SESSION['old']);
unset($_SESSION['error']);

$page_title = 'Register';
require_once __DIR__ . '/../../includes/auth_header.php';
?>


<form action="/auth/register/register.php" method="POST">
    <h3>Register</h3>
    <input type="text" name="first_name" placeholder="First Name"
        value="<?= htmlspecialchars($old['first_name'] ?? '') ?>">
    <?php if (isset($error['first_name'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['first_name']) ?></p>
    <?php endif; ?>
    <br>
    <input type="text" name="last_name" placeholder="Last Name"
        value="<?= htmlspecialchars($old['last_name'] ?? '') ?>">
    <?php if (isset($error['last_name'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['last_name']) ?></p>
    <?php endif; ?>
    <br>
    <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
    <?php if (isset($error['username'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['username']) ?></p>
    <?php endif; ?>
    <br>
    <input type="text" name="phone" placeholder="Phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
    <?php if (isset($error['phone'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['phone']) ?></p>
    <?php endif; ?>
    <br>
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    <?php if (isset($error['email'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['email']) ?></p>
    <?php endif; ?>
    <br>
    <input type="password" name="password" placeholder="Password">
    <?php if (isset($error['password'])): ?>
        <p style="color:red"><?= htmlspecialchars($error['password']) ?></p>
    <?php endif; ?>
    <br>
    <button type="submit">Register</button>
    <br>
    <a class="auth-link" href="/auth/login/form.php">Already have an account? Log in</a>
  
</form>

<?php require_once __DIR__ . '/../../includes/auth_footer.php'; ?>
