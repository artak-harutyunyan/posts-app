<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
$admin = require_admin($db);

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: form.php');
    exit();
}
$is_blocked = filter_var($_POST['is_blocked'] ?? '', FILTER_VALIDATE_INT);

$id = filter_var($_POST['user_id'] ?? '', FILTER_VALIDATE_INT);
if($id === false ) {
    header('location: form.php');
    exit();
}

if($id === (int) $admin['id']) {
    header('location: form.php');
    exit();
} 
if ($is_blocked !== 0 && $is_blocked !== 1) {
    header('Location: form.php');
    exit();
} 

$stmt = mysqli_prepare($db, 'update users set is_blocked = ? where id = ?');
mysqli_stmt_bind_param($stmt, 'ii', $is_blocked, $id);
mysqli_stmt_execute($stmt);

header('Location: form.php');
exit();
