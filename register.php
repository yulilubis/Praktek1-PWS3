<?php
session_start();
require_once 'koneksi.php';

$pesan = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Bagian A
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = $_POST['role'] ?? 'teknisi';

    // Bagian B
    if (empty($username) || empty($password) || empty($role)) {
        $error = "Semua kolom wajib diisi!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal harus 6 karakter!";
    } else {
        try {
            // Bagian B1
            $stmtCek = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmtCek->execute(['username' => $username]);

            if ($stmtCek->fetch()) {
                $error = "Username '$username' sudah digunakan, silakan pilih username lain!";
            } else {
                // Bagian B2
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Bagian B3
                $stmtInsert = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
                $sukses = $stmtInsert->execute([
                    'username' => $username,
                    'password' => $hashed_password,
                    'role'     => $role
                ]);

                if ($sukses) {
                    $pesan = "Registrasi berhasil! Silakan <a href='login.php'>Login di sini</a>.";
                }
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan database: " . $e->getMessage();
        }
    }
}
?>


<!-- Bagian C -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register User Baru</title>
</head>
<body>
    <h2>Form Pendaftaran User Baru</h2>

    <?php if ($pesan): ?>
        <p style="color: green;"><?= $pesan ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" placeholder="Masukkan username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" placeholder="Masukkan password" required><br><br>

        <label>Role / Peran:</label><br>
        <select name="role" required>
            <option value="teknisi">Teknisi</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">Daftar User Baru</button>
    </form>

    <p><a href="login.php">Kembali ke Halaman Login</a></p>
</body>
</html>