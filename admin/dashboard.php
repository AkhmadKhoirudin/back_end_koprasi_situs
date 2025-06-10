<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 15px;
        }
        .content-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .content-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #2575fc;
        }
        .badge-new {
            background-color: #ff4757;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Sambutan -->
        <div class="card welcome-card mb-4 border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="card-title">Selamat Datang, Admin!</h2>
                        <p class="card-text">Kelola semua konten website Anda dari sini. Mulai buat konten baru atau kelola konten yang sudah ada.</p>
                        <a href="#" class="btn btn-light">Lihat Panduan</a>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="https://via.placeholder.com/200x150/ffffff/2575fc?text=Content" alt="Content" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Buat Konten Baru -->
        <h3 class="mb-3"><i class="fas fa-plus-circle me-2"></i>Buat Konten Baru</h3>
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card content-card h-100 text-center p-3">
                    <div class="content-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5>Artikel</h5>
                    <p class="text-muted">Buat artikel baru untuk blog</p>
                    <a href="create_article.php" class="btn btn-outline-primary btn-sm stretched-link">Buat Sekarang</a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card content-card h-100 text-center p-3">
                    <div class="content-icon">
                        <i class="fas fa-image"></i>
                    </div>
                    <h5>Galeri</h5>
                    <p class="text-muted">Upload gambar ke galeri</p>
                    <a href="create_gallery.php" class="btn btn-outline-primary btn-sm stretched-link">Buat Sekarang</a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card content-card h-100 text-center p-3">
                    <div class="content-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h5>Video</h5>
                    <p class="text-muted">Tambahkan video baru</p>
                    <a href="create_video.php" class="btn btn-outline-primary btn-sm stretched-link">Buat Sekarang</a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card content-card h-100 text-center p-3">
                    <div class="content-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5>Event</h5>
                    <p class="text-muted">Buat event atau agenda</p>
                    <a href="create_event.php" class="btn btn-outline-primary btn-sm stretched-link">Buat Sekarang</a>
                </div>
            </div>
        </div>

        <!-- Daftar Konten Terbaru -->
      
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>