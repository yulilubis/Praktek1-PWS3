<?php
// Set Header agar dikembalikan sebagai JSON
header('Content-Type: application/json; charset=utf-8');

require_once 'koneksi.php';

try {
    // Ambil data dari database
    $stmt = $pdo->query("SELECT id, nama_alat, jumlah, kondisi FROM alat_lab");
    $data = $stmt->fetchAll();

    // Response HTTP 200 OK
    http_response_code(200);
    echo json_encode([
        'status'    => 'success',
        'code'      => 200,
        'message'   => 'Data alat lab berhasil diambil',
        'data'      => $data
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    // Response HTTP 500 Internal Server Error jika database error
    http_response_code(500);
    echo json_encode([
        'status'    => 'error',
        'code'      => 500,
        'message'   => 'Terjadi kesalahan pada server database',
        'error'     => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>