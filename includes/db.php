<?php
try {
    $db = new mysqli('localhost', 'root', '', 'posts_app');
    $db->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    exit('Database connection failed.');
}