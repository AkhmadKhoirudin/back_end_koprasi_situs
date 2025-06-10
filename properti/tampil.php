<?php
// Koneksi ke database
include '../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0; 

$sql = "SELECT * FROM content WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konten</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

<?php require 'nav/header.php'; ?>

        <div class="max-w-3xl mx-auto my-10 p-6 bg-white shadow-lg rounded-lg">
            <?php
            // Tampilkan data
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<h2 class='text-2xl font-bold mb-4 text-center'>" . htmlspecialchars($row['judul']) . "</h2>";
                    echo "<img src='data:image/jpeg;base64,". $row['gambar']. "' alt='Thumbnail' class='w-full h-64 object-cover rounded-lg mb-4'> <br>";
                    echo "<div class='prose'>" . htmlspecialchars_decode($row['isi']) . "</div>";
                }
            } else {
                echo "<p class='text-red-500 text-center'>Data tidak ditemukan</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>

    <?php require 'nav/footer.php'; ?>

</body>
</html>
