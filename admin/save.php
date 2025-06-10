<?php
header('Content-Type: application/json');

// Koneksi ke database
$servername = "localhost";
$username = "root";  // Ganti dengan username database Anda
$password = "";      // Ganti dengan password database Anda
$dbname = "cms";     // Pastikan ini sesuai dengan nama database Anda

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception('Koneksi database gagal: ' . $conn->connect_error);
    }

    // Ambil data dari form
    $judul = $_POST['judul'] ?? '';
    $isi = $_POST['content'] ?? '';  // Sesuai dengan nama field 'isi' di database
    $keterangan = $_POST['keterangan'] ?? '';
    $gambarBase64 = $_POST['gambar'] ?? '';

    // Validasi data
    if (empty($judul) || empty($isi)) {
        throw new Exception('Judul dan isi tidak boleh kosong!');
    }

    // Simpan ke database - sesuaikan dengan nama kolom di tabel
    $sql = "INSERT INTO content (judul, isi, keterangan, gambar, created_at) 
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare statement gagal: ' . $conn->error);
    }

    $stmt->bind_param("ssss", $judul, $isi, $keterangan, $gambarBase64);

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Data berhasil disimpan!',
            'id' => $stmt->insert_id
        ];
    } else {
        throw new Exception('Gagal menyimpan data: ' . $stmt->error);
    }

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
    echo json_encode($response);
}
?>