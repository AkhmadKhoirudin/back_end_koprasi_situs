<?php
require '../koneksi.php'; // Pastikan ada file koneksi.php untuk koneksi ke database


// Hapus data jika ada permintaan delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM content WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>alert('Data berhasil dihapus!'); window.location.href='content.php';</script>";
}



// Ambil semua data untuk ditampilkan
$result = $conn->query("SELECT * FROM content");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Konten</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-relaxed">

<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center text-blue-700 mb-8">Daftar Konten</h1>

    <div class="mb-6 text-right">
        <a href="creat.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
            + Buat Konten Baru
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-sm font-medium uppercase">Keterangan</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Thumbnail</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-800"><?= htmlspecialchars($row['judul']) ?></td>
                        <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($row['gambar']): ?>
                                <img src="data:image/*;base64,<?= $row['gambar'] ?>" alt="Thumbnail" class="w-16 h-16 object-cover rounded">
                            <?php else: ?>
                                <span class="text-gray-400 italic">Tidak ada gambar</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                Edit
                            </a>
                            <a href="content.php?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?');"
                               class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
