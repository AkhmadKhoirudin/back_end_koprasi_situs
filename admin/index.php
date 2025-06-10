


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dynamic Sidebar with Iframe</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
    }

    .d-flex {
      height: calc(100vh - 56px); /* Navbar height default Bootstrap is 56px */
    }

    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      color: white;
      overflow-y: auto;
    }

    .main-content {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    #content-frame {
      flex-grow: 1;
      width: 100%;
      height: 100%;
      border: none;
    }

    .nav-link.active {
      background-color: rgba(255, 255, 255, 0.1);
      font-weight: bold;
    }

    @media (max-width: 992px) {
      .sidebar {
        position: fixed;
        z-index: 1040;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }

      .sidebar.show {
        transform: translateX(0);
      }
    }

    .offcanvas-backdrop {
      z-index: 1039;
    }

    .dropdown-menu {
      position: static !important;
      float: none;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand ms-2" href="#">Admin Panel</a>
    </div>
  </nav>

  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-dark text-white p-3 offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
      <div class="offcanvas-header d-lg-none">
        <h5 class="offcanvas-title">Menu Navigation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>

      <div class="offcanvas-body p-0">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a href="#" class="nav-link text-white active" onclick="loadPage('dashboard.php', this)">
              <i class="fas fa-home me-2"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white" onclick="loadPage('user.php', this)">
              <i class="fas fa-users me-2"></i> User Management
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white" onclick="loadPage('creat.php', this)">
              <i class="fas fa-boxes me-2"></i> creat artikel
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white" onclick="loadPage('content.php', this)">
              <i class="fas fa-shopping-cart me-2"></i> list artikel
            </a>
          </li>
          
          
          <li class="nav-item">
            <a href="#" class="nav-link text-white" onclick="loadPage('settings/general.php', this)">
              <i class="fas fa-cog me-2"></i> Settings
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content p-0">
      <iframe id="content-frame" src="dashboard.php"></iframe>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function loadPage(pageUrl, clickedElement) {
      // Hilangkan semua class 'active'
      document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));

      // Tambahkan class 'active' ke elemen yang diklik (jika ada)
      if (clickedElement && clickedElement.classList.contains('nav-link')) {
        clickedElement.classList.add('active');
      }

      // Ganti halaman di iframe
      document.getElementById('content-frame').src = pageUrl;

      // Tutup sidebar jika di mode mobile
      if (window.innerWidth < 992) {
        const sidebar = bootstrap.Offcanvas.getInstance(document.getElementById('sidebar'));
        if (sidebar) sidebar.hide();
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      loadPage('dashboard.php', document.querySelector('.nav-link.active'));
    });
  </script>

</body>
</html>
