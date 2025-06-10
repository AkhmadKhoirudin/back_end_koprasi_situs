<?php
require '../koneksi.php';

header('Content-Type: application/json');

// Menangkap input dari POST
$id = $_POST['id'] ?? '';
$judul = $_POST['judul'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$content = $_POST['isi'] ?? '';
$gambar = $_POST['gambar'] ?? '';

$errors = [];

// Validasi input
if (empty($id) || !is_numeric($id)) $errors[] = "ID tidak valid.";
if (empty($judul)) $errors[] = "Judul tidak boleh kosong.";
if (empty($keterangan)) $errors[] = "Keterangan tidak boleh kosong.";
if (empty($content)) $errors[] = "Isi tidak boleh kosong.";

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode("\n", $errors)]);
    exit;
}

// Validasi gambar jika ada
if (!empty($gambar)) {
    $decodedImage = base64_decode($gambar, true);
    if (!$decodedImage) {
        echo json_encode(['success' => false, 'message' => 'Format gambar tidak valid.']);
        exit;
    }
}

try {
    // Menyiapkan query SQL
    if (!empty($gambar)) {
        $stmt = $conn->prepare("UPDATE content SET judul=?, keterangan=?, isi=?, gambar=? WHERE id=?");
        $stmt->bind_param("ssssi", $judul, $keterangan, $content, $gambar, $id);
    } else {
        $stmt = $conn->prepare("UPDATE content SET judul=?, keterangan=?, isi=? WHERE id=?");
        $stmt->bind_param("sssi", $judul, $keterangan, $content, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
exit;
?>
