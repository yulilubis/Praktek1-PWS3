<?php
session_start();
require_once 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        // Prepared Statement untuk mencegah SQL Injection
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username: username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // Verifikasi password yang ter-hash
        if ($user && password_verify($password, $user['password'])) {
        // Set Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;
    } else {    
        $error = "Username atau password salah!";
    }
} else {
    $error = "Semua kolom wajib diisil";
    }
}
?>