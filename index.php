<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$user = current_user($db);
if($user === null) {
    header('Location: /auth/login/form.php');
    exit();
}else {
    header('Location: /posts/my/form.php');
    exit();
}
