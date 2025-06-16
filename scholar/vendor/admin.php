<?php
// admin/index.php
session_start();

// Skontroluj či je používateľ prihlásený a má admin práva
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: ../scholar/login.php");
    exit;
}
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    // Nesmieš sa sem dostať
    header("Location: ../index.php");
    exit;
}


// Include database configuration if needed
// require_once '../config/db_config.php';
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gorm</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #2c3e50;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-bottom: 1px solid #34495e;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #3498db;
            color: #fff;
        }
        .main-content {
            padding: 20px;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-card h3 {
            margin: 0;
            font-size: 2.5rem;
        }
        .stats-card p {
            margin: 0;
            opacity: 0.8;
        }
        .admin-header {
            background-color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky pt-3">
                <div class="text-center py-3">
                    <h4 class="text-white">GORM Admin</h4>
                    <small class="text-muted">Vitaj, <?php echo htmlspecialchars($_SESSION['username']); ?></small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#dashboard" data-section="dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#events" data-section="events">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Správa eventov
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#users" data-section="users">
                            <i class="fas fa-users me-2"></i>
                            Správa používateľov
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery" data-section="gallery">
                            <i class="fas fa-images me-2"></i>
                            Galéria
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#settings" data-section="settings">
                            <i class="fas fa-cog me-2"></i>
                            Nastavenia
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-danger" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Odhlásiť sa
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="admin-header">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h1 class="h2">Admin Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div id="dashboard-content" class="content-section">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h3>150</h3>
                            <p><i class="fas fa-calendar-check me-2"></i>Celkovo eventov</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <h3>1,250</h3>
                            <p><i class="fas fa-users me-2"></i>Registrovaných používateľov</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <h3>25</h3>
                            <p><i class="fas fa-calendar me-2"></i>Eventy tento mesiac</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <h3>95%</h3>
                            <p><i class="fas fa-thumbs-up me-2"></i>Spokojnosť</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Posledné aktivity</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Nový event pridaný</strong>
                                            <br><small class="text-muted">ZOO Bojnice - Detské akcie</small>
                                        </div>
                                        <small class="text-muted">pred 2 hodinami</small>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Nová registrácia</strong>
                                            <br><small class="text-muted">Používateľ: johndoe</small>
                                        </div>
                                        <small class="text-muted">pred 5 hodinami</small>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Event aktualizovaný</strong>
                                            <br><small class="text-muted">Gymnázium Bratislava</small>
                                        </div>
                                        <small class="text-muted">včera</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Rýchle akcie</h5>
                            </div>
                            <div class="card-body text-center">
                                <button class="btn btn-primary btn-lg mb-3 w-100">
                                    <i class="fas fa-plus me-2"></i>Pridať nový event
                                </button>
                                <button class="btn btn-success btn-lg mb-3 w-100">
                                    <i class="fas fa-user-plus me-2"></i>Pridať používateľa
                                </button>
                                <button class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Zobraziť reporty
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Content -->
            <div id="events-content" class="content-section" style="display: none;">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Správa eventov</h5>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Pridať event
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Názov</th>
                                    <th>Kategória</th>
                                    <th>Dátum</th>
                                    <th>Účastníci</th>
                                    <th>Akcie</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>ZOO Bojnice</td>
                                    <td>Detské akcie</td>
                                    <td>16 Feb 2021</td>
                                    <td>150+</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Gymnázium Bratislava</td>
                                    <td>Školy</td>
                                    <td>2 máj 2024</td>
                                    <td>240+</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Content -->
            <div id="users-content" class="content-section" style="display: none;">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Správa používateľov</h5>
                        <button class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Pridať používateľa
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Používateľské meno</th>
                                    <th>Email</th>
                                    <th>Rola</th>
                                    <th>Registrácia</th>
                                    <th>Akcie</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>admin</td>
                                    <td>admin@gorm.sk</td>
                                    <td><span class="badge bg-danger">Admin</span></td>
                                    <td>01.01.2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>testuser</td>
                                    <td>user@test.sk</td>
                                    <td><span class="badge bg-primary">User</span></td>
                                    <td>15.03.2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Content -->
            <div id="gallery-content" class="content-section" style="display: none;">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Správa galérie</h5>
                        <button class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Nahrať obrázky
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Event 1">
                                    <div class="card-body">
                                        <p class="card-text">ZOO Bojnice Event</p>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Event 2">
                                    <div class="card-body">
                                        <p class="card-text">Gymnázium Event</p>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div id="settings-content" class="content-section" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h5>Systémové nastavenia</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="siteName" class="form-label">Názov stránky</label>
                                        <input type="text" class="form-control" id="siteName" value="Gorm">
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactEmail" class="form-label">Kontaktný email</label>
                                        <input type="email" class="form-control" id="contactEmail" value="info@gorm.sk">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Telefón</label>
                                        <input type="text" class="form-control" id="phone" value="+421 XXX XXX XXX">
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Adresa</label>
                                        <textarea class="form-control" id="address" rows="3">Bratislava, Slovensko</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Uložiť zmeny</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Simple navigation
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.sidebar .nav-link[data-section]');
        const contentSections = document.querySelectorAll('.content-section');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all links
                navLinks.forEach(l => l.classList.remove('active'));
                // Add active class to clicked link
                this.classList.add('active');

                // Hide all content sections
                contentSections.forEach(section => section.style.display = 'none');

                // Show selected content section
                const targetSection = this.getAttribute('data-section') + '-content';
                document.getElementById(targetSection).style.display = 'block';
            });
        });
    });
</script>
</body>
</html>