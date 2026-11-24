<?php
require_once '../../includes/functions.php';
require_once '../../includes/db.php';
$admin = require_admin($db);

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: form.php');
    exit();
}

$id = filter_var($_POST['post_id'] ?? '', FILTER_VALIDATE_INT);
if($id === false ) {
    header('location: form.php');
    exit();
}


$is_blocked = filter_var($_POST['is_blocked'] ?? '', FILTER_VALIDATE_INT);
if ($is_blocked !== 0 && $is_blocked !== 1) {
    header('Location: form.php');
    exit();
}

$stmt = mysqli_prepare($db, 'update posts set is_blocked = ? where id = ? ');
mysqli_stmt_bind_param($stmt, 'ii', $is_blocked, $id);
mysqli_stmt_execute($stmt);

header('Location: form.php');
exit();
