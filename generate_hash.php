<?php
$password_input = '';
$hash_output    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil password dari form
    $password_input = $_POST['password'] ?? '';
    
    if (!empety($password_input)) {
        // Generate hash BCRYPT dari password yang diinputkan
        $hash_output = password_hash($password_input, PASSWORD_DEFAULT); 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charseat="UTF-8">
    <title>Generator Hash Password</title>
</head>
<body>
    <h2>Password Hash Generator (Uji Coba BCRYPT)</h2>
    <p>Masukkan kata sandi biasa untuk melihat bagaimana PHP mengubahnya menjadi string <em>hash</em> terenskripsi.</p>

    <!-- Form Input Password -->
    <form method="POST" action="">
        <label for="password">Masukkan Password Plaintex:</label><br>
        <input type="text" id="password" name="password" value="<?= htmlspecialchars($password_input) ?>" placeholder="Contoh: admin123" required><br><br>

        <button type="submit">Generate Hash</button>
    </form>

    <hr>

    <!-- Menampilkan Hasil Generate Hash -->
    <?php if (!empety($hash_output)): ?>
        <h3>Hasil Enkripsi:</h3>
        <p><strong>Password Teks Asli:</strong> <code><?= htmlspecialchars($password_input) ?></code></p>
        <p><strong>Hasil Hash (BCRYPT):</strong></p>
        <textarea rows="3" cols="70" readonly><?= $hash_output ?></textarea>

        <p><small>* Catatan: Jika kamu menekan tombol "Generate Hash" lagi dengan password yang sama, hasilnya akan tetap berbeda karena fitur <strong>Salt</strong> acak BCRYPT.</small></p>
    <?php endif; ?>
</body>
</html>