<?php

require '../koneksi.php';

$sql = "SELECT * FROM content ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Carousel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .carousel {
            background-color: black;
        }

        .carousel-item img {
            max-height: 450px;
            object-fit: cover;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            padding: 15px;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-dark text-white">


<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
        if ($result->num_rows > 0) {
            $active = true; // Menjadikan slide pertama aktif
            while ($row = $result->fetch_assoc()) {
                echo '<div class="carousel-item ' . ($active ? 'active' : '') . '">';
                echo '<img src="data:image/jpeg;base64,' . $row["gambar"] . '" class="d-block w-100" alt="Slide">';
                echo '<div class="carousel-caption">';
                echo '<h5>' . $row["judul"] . '</h5>';
                echo $row["isi"];
                echo '</div></div>';
                $active = false;
            }
        } else {
            echo '<div class="carousel-item active"><h3 class="text-center">Tidak ada data</h3></div>';
        }
        ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<script>
    document.getElementById("carouselExampleCaptions").addEventListener("contextmenu", function(event) {
        event.preventDefault();
        document.querySelector(".carousel-control-prev").click();
    });

    document.getElementById("carouselExampleCaptions").addEventListener("click", function(event) {
        document.querySelector(".carousel-control-next").click();
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
