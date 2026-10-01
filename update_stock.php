<?php
session_start();
require_once 'koneksi.php';

// Cek autentikasi
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pesan = '';
$error = '';

// Ambil daftar alat untuk dropdown
$stmtAlat = $pdo->query("SELECT id, nama_alat, jumlah FROM alat_lab");
$daftarAlat = $stmtAlat->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_alat = $_POST['id_alat'] ?? null;
    $jumlah = $_POST['jumlah'] ?? null;

    // Validasi input: Tidak boleh kurang dari 0
    if ($jumlah < 0 || !is_numeric($jumlah)) {
        $error = "Jumlah stock tidak boleh negatif atau berupa teks!";
    } elseif (empty($id_alat)) {
        $error = "Pilih alat terlebih dahulu!";
    } else {
        // Update data menggunakan Prepared Statement
        $stmtUpdate = $pdo->prepare("UPDATE alat_lab SET jumlah = :jumlah WHERE id = :id");
        $sukses = $stmtUpdate->execute([
            'jumlah' => (int)$jumlah,
            'id' => $id_alat
        ]);

        if ($sukses) {
            $pesan = "Berhasil mengupdate stock alat!";
            // Refresh data alat
            $stmtAlat = $pdo->query("SELECT id, nama_alat, jumlah FROM alat_lab" );
            $daftarAlat = $stmtAlat->fetchAll();
        } else {
            $error = "Gagal mengupdate stock di database.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Stock Alat Lab</title>
</head>
<body>
    <h2>Update Stok Alat Laboratorium</h2>
    <p> <a href="dashboard.php">&laquo; Kembali ke Dashboard</a> </p>

    <?php if ($pesan): ?> <p style="color: green;"><?= htmlspecialchars($pesan) ?> </p> <?php endif; ?> 
    <?php if ($error): ?> <p style="color: red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="POST" action="">
        <label>Pilih Alat:</label><br>
        <select name="id_alat" required>
            <option value="">-- Pilih Alat --</option>
            <?php foreach ($daftarAlat as $alat): ?>
                <option value="<?= $alat['id_alat'] ?>">
                    <?= htmlspecialchars($alat['nama_alat']) ?>(Stock Sekarang: <?= $alat['jumlah'] ?>)
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label>Jumlah Stock Baru:</label><br>
        <input type="number"name="jumlah"min="0"required > <br><br>

        <button type="submit">Update Stock</button>
    </form>
</body>
</html>