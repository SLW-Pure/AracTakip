<?php
session_start();

// Kullanıcı oturumu kontrol
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
