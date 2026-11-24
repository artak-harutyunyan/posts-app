<?php
session_start();
require_once '../../includes/db.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit;
}

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$error = [];

$_SESSION['old'] = [
    'first_name' => $first_name,
    'last_name' => $last_name,
    'username' => $username,
    'email' => $email,
    'phone' => $phone,
];

if ($first_name === '') {
    $error['first_name'] = 'First name is required';
} elseif (strlen($first_name) > 50) {
    $error['first_name'] = 'First name is too long';
}

if ($last_name === '') {
    $error['last_name'] = 'Last name is required';
} elseif (strlen($last_name) > 50) {
    $error['last_name'] = 'Last name is too long';
}

if ($username === '') {
    $error['username'] = 'Username is required';
} elseif (strlen($username) > 50) {
    $error['username'] = 'Username is too long';
} else {
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error['username'] = 'Username already exists';
    }
    $stmt->close();
}

if ($email === '') {
    $error['email'] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
    $error['email'] = 'Email is not valid';
} else {
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error['email'] = 'Email already used';
    }
    $stmt->close();
}

if ($phone === '') {
    $error['phone'] = 'Phone is required';
} elseif (strlen($phone) > 20) {
    $error['phone'] = 'Phone is too long';
}

if (strlen($password) < 8) {
    $error['password'] = 'Password must be at least 8 characters long';
} elseif (strlen($password) > 72) {
    $error['password'] = 'Password must be at most 72 characters long';
}

if (count($error) > 0) {
    $_SESSION['error'] = $error;
    header('Location: form.php');
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (first_name, last_name, username, phone, email, password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $first_name, $last_name, $username, $phone, $email, $password_hash);
$stmt->execute();
$stmt->close();

unset($_SESSION['old']);
header('Location: ../login/form.php');
exit;