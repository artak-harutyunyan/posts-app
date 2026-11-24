<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login($db)
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /auth/login/form.php');
        exit();
    }

    $stmt = $db->prepare("SELECT id, role, username, is_blocked FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || (int) $user['is_blocked'] === 1) {
        $_SESSION = [];
        session_destroy();
        header('Location: /auth/login/form.php');
        exit();
    }
    return $user;
}

function require_admin($db)
{
    $user = require_login($db);
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        exit('Access denied');
    }
    return $user;
}

function current_user($db)
{
    if(!isset($_SESSION['user_id'])) {
        return null;
    }else  {
        $stmt = $db->prepare("SELECT id, role, username, is_blocked FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || (int) $user['is_blocked'] === 1) {
            $_SESSION = [];
            session_destroy();
            return null;
        }
        return $user;
    }
}