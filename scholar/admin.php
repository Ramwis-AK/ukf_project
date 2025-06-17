<?php
// Admin panel - admin.php
define('DB_ACCESS_ALLOWED', true);
session_start();

// Include potrebné súbory
require_once 'config/db_config.php';
require_once 'functions/helpers.php';

// Skontroluj, či je používateľ prihlásený a je admin
if (!isLoggedIn() || !isAdmin()) {
    header("Location: includes/login.php");
    exit;
}

$currentUser = getCurrentUser();
$pageTitle = "Admin Panel - Scholar";
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>

    <!-- Bootstrap CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">

    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
        }

        body {
            background-color: var(--light-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .sidebar {
            background: white;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 0;
        }

        .sidebar .nav-link {
            color: var(--dark-color);
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .main-content {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }

        .stats-card {
            text-align: center;
            padding: 30px 20px;
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stats-label {
            font-size: 1.1rem;
            color: #666;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .quick-action-btn {
            display: block;
            padding: 20px;
            background: white;
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            text-decoration: none;
            color: var(--primary-color);
            text-align: center;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .quick-action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            text-decoration: none;
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap me-2"></i>
            <button id="logoutBtn">Odhlásiť sa</button>
            Scholar Admin
        </a>

        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-1"></i>
                    <?php echo htmlspecialchars($currentUser['username']); ?>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index.php">
                            <i class="fas fa-home me-2"></i>Domovská stránka
                        </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="includes/logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Odhlásiť sa
                        </a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <nav class="nav flex-column">
                <a class="nav-link active" href="#dashboard" data-section="dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
                <a class="nav-link" href="#users" data-section="users">
                    <i class="fas fa-users me-2"></i>
                    Používatelia
                </a>
                <a class="nav-link" href="#courses" data-section="courses">
                    <i class="fas fa-book me-2"></i>
                    Kurzy
                </a>
                <a class="nav-link" href="#content" data-section="content">
                    <i class="fas fa-edit me-2"></i>
                    Obsah stránky
                </a>
                <a class="nav-link" href="#settings" data-section="settings">
                    <i class="fas fa-cog me-2"></i>
                    Nastavenia
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <!-- Dashboard Section -->
            <div id="dashboard-section" class="content-section">
                <div class="welcome-banner">
                    <h1>Vitajte v Admin Paneli</h1>
                    <p class="mb-0">Prehľad a správa vašej online školy</p>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <a href="#" class="quick-action-btn" data-section="users">
                        <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                        Pridať používateľa
                    </a>
                    <a href="#" class="quick-action-btn" data-section="courses">
                        <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                        Nový kurz
                    </a>
                    <a href="#" class="quick-action-btn" data-section="content">
                        <i class="fas fa-edit fa-2x mb-2"></i><br>
                        Upraviť obsah
                    </a>
                    <a href="#" class="quick-action-btn" data-section="settings">
                        <i class="fas fa-cog fa-2x mb-2"></i><br>
                        Nastavenia
                    </a>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card stats-card">
                            <div class="stats-number text-primary" id="users-count">-</div>
                            <div class="stats-label">Používatelia</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stats-card">
                            <div class="stats-number text-success" id="courses-count">-</div>
                            <div class="stats-label">Kurzy</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stats-card">
                            <div class="stats-number text-warning" id="team-count">-</div>
                            <div class="stats-label">Tím</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stats-card">
                            <div class="stats-number text-info" id="testimonials-count">-</div>
                            <div class="stats-label">Recenzie</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Section -->
            <div id="users-section" class="content-section" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Správa používateľov</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Používateľské meno</th>
                                    <th>Email</th>
                                    <th>Rola</th>
                                    <th>Akcie</th>
                                </tr>
                                </thead>
                                <tbody id="users-table-body">
                                <!-- Dynamicky načítané -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Section -->
            <div id="courses-section" class="content-section" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Správa kurzov</h3>
                    </div>
                    <div class="card-body">
                        <p>Funkcia správy kurzov bude implementovaná...</p>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div id="content-section" class="content-section" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Správa obsahu</h3>
                    </div>
                    <div class="card-body">
                        <p>Funkcia správy obsahu bude implementovaná...</p>
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div id="settings-section" class="content-section" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Nastavenia systému</h3>
                    </div>
                    <div class="card-body">
                        <p>Funkcia nastavení bude implementovaná...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
    // Navigation handling
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-link, .quick-action-btn');
        const contentSections = document.querySelectorAll('.content-section');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetSection = this.getAttribute('data-section');
                if (!targetSection) return;

                // Update active nav link
                document.querySelectorAll('.nav-link').forEach(nl => nl.classList.remove('active'));
                document.querySelectorAll(`.nav-link[data-section="${targetSection}"]`).forEach(nl => nl.classList.add('active'));

                // Show target section
                contentSections.forEach(section => {
                    section.style.display = 'none';
                });

                const targetElement = document.getElementById(targetSection + '-section');
                if (targetElement) {
                    targetElement.style.display = 'block';

                    // Load section data
                    loadSectionData(targetSection);
                }
            });
        });

        // Load initial data
        loadDashboardStats();
    });

    // Load dashboard statistics
    function loadDashboardStats() {
        // Simulate API calls - replace with actual AJAX calls
        fetch('admin_api.php?action=get_stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('users-count').textContent = data.users || '0';
                document.getElementById('courses-count').textContent = data.courses || '0';
                document.getElementById('team-count').textContent = data.team || '0';
                document.getElementById('testimonials-count').textContent = data.testimonials || '0';
            })
            .catch(error => {
                console.log('Stats not available yet');
                // Set default values
                document.getElementById('users-count').textContent = '1';
                document.getElementById('courses-count').textContent = '0';
                document.getElementById('team-count').textContent = '0';
                document.getElementById('testimonials-count').textContent = '0';
            });
    }

    // Load section specific data
    function loadSectionData(section) {
        switch(section) {
            case 'users':
                loadUsers();
                break;
            case 'courses':
                loadCourses();
                break;
            // Add more cases as needed
        }
    }

    // Load users data
    function loadUsers() {
        fetch('admin_api.php?action=get_users')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('users-table-body');
                tbody.innerHTML = '';

                data.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                            <td>${user.user_ID}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td><span class="badge bg-${user.role === 'admin' ? 'danger' : 'primary'}">${user.role}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editUser(${user.user_ID})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.user_ID})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.log('Users data not available yet');
                document.getElementById('users-table-body').innerHTML =
                    '<tr><td colspan="5" class="text-center">Údaje o používateľoch nie sú k dispozícii</td></tr>';
            });
    }

    // Placeholder functions
    function loadCourses() {
        console.log('Loading courses...');
    }

    function editUser(userId) {
        console.log('Edit user:', userId);
    }

    function deleteUser(userId) {
        if (confirm('Naozaj chcete zmazať tohto používateľa?')) {
            console.log('Delete user:', userId);
        }
    }
</script>
<script>
    document.getElementById('logoutBtn').addEventListener('click', () => {
        fetch('admin_api.php?action=logout')
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.href = 'login.php';  // alebo kam chceš presmerovať po odhlásení
                } else {
                    alert('Chyba pri odhlasovaní');
                }
            })
            .catch(() => alert('Chyba pri odhlasovaní'));
    });
</script>
</body>
</html>
</html>