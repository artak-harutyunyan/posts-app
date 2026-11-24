<?php
session_start();
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$error = [];
if ($username === '') {
    $error['username'] = 'Please fill in username';

}
if ($password === '') {
    $error['password'] = 'Please fill in password';

}

if (count($error) > 0) {
    $_SESSION['old'] = ['username' => $username];
    $_SESSION['error'] = $error;
    header('Location: form.php');
    exit;
}

$stmt = $db->prepare("SELECT id, username, password, is_blocked FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        if ((int) $user['is_blocked'] === 1) {
            $_SESSION['error'] = ['form' => 'Your account is blocked. Please contact support.'];
            header('Location: form.php');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        header('Location: /main.php');
        exit;
    }
}

$_SESSION['error'] = ['form' => 'Invalid username or password'];
header('Location: form.php');
exit;
