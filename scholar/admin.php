<?php
// Admin panel - admin.php

// Definuje konštantu pre kontrolu oprávneného prístupu do databázy
define('DB_ACCESS_ALLOWED', true);

// Spustí PHP session pre prácu s prihlásením používateľa
session_start();

// Načítanie konfiguračných a pomocných súborov
require_once 'config/db_config.php';         // Konfigurácia databázy
require_once 'functions/helpers.php';        // Pomocné funkcie ako login, rola, atď.

// Overenie, či je používateľ prihlásený a či má administrátorské oprávnenia
// Ak nie, presmeruje ho na prihlasovaciu stránku
if (!Helpers::isLoggedIn() || !Helpers::isAdmin()) {
    header("Location: includes/login.php");
    exit; // Ukončí skript, aby sa nedostalo k zvyšku kódu
}

// Získa údaje aktuálne prihláseného používateľa (napr. meno, ID)
$currentUser = Helpers::getCurrentUser();

// Nastaví názov stránky pre zobrazovanie v <title>
$pageTitle = "Admin Panel - Scholar";
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>

    <!-- Bootstrap CSS pre responzívny dizajn a základné štýly -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome ikony -->
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

<!-- Hlavná navigácia v hornej časti stránky -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <!-- Názov admin panelu s ikonou -->
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap me-2"></i>
            Scholar Admin
        </a>

        <!-- Tlačidlo na odhlásenie (funkčné cez JS alebo priamy link) -->
        <button id="logoutBtn" class="btn btn-outline-light">Odhlásiť sa</button>

        <!-- Sekcia napravo s menom prihláseného používateľa a dropdown menu -->
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-1"></i>
                    <?php echo htmlspecialchars($currentUser['username']); ?> <!-- Bezpečné zobrazenie mena -->
                </a>
                <ul class="dropdown-menu">
                    <!-- Odkaz späť na hlavnú stránku -->
                    <li><a class="dropdown-item" href="index.php">
                            <i class="fas fa-home me-2"></i>Domovská stránka
                        </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <!-- Odkaz na odhlásenie -->
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

        <!-- Bočný panel s navigáciou medzi sekciami admin rozhrania -->
        <div class="col-md-3 col-lg-2 sidebar">
            <nav class="nav flex-column">
                <!-- Každý odkaz má atribút `data-section` na prepínanie viditeľného obsahu -->
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

        <!-- Hlavná obsahová časť (pravá strana) -->
        <div class="col-md-9 col-lg-10 main-content">

            <!-- Sekcia dashboardu -->
            <div id="dashboard-section" class="content-section">
                <div class="welcome-banner">
                    <h1>Vitajte v Admin Paneli</h1>
                </div>

                <!-- Rýchle akcie ako pridanie používateľa, kurzu, atď. -->
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

                <!-- Prehľadové karty so štatistikami -->
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

            <!-- Sekcia pre správu používateľov -->
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
                                <!-- Tu sa dynamicky cez JS načítajú dáta používateľov -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sekcia pre správu kurzov -->
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

            <!-- Sekcia pre správu obsahu stránky -->
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

            <!-- Sekcia pre nastavenia systému -->
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

        </div> <!-- Koniec hlavného obsahu -->
    </div>
</div>

<!-- Načítanie potrebných JavaScript knižníc (jQuery a Bootstrap) -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>


<script>
    // Po načítaní DOMu sa inicializuje navigácia a základné dáta
    document.addEventListener('DOMContentLoaded', function() {
        // Výber všetkých navigačných prvkov a sekcií obsahu
        const navLinks = document.querySelectorAll('.nav-link, .quick-action-btn');
        const contentSections = document.querySelectorAll('.content-section');

        // Každému odkazu sa priradí klik handler
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault(); // Zamedzí predvolenému správaniu odkazu

                const targetSection = this.getAttribute('data-section');
                if (!targetSection) return;

                // Odstráni triedu 'active' zo všetkých odkazov navigácie
                document.querySelectorAll('.nav-link').forEach(nl => nl.classList.remove('active'));
                // Označí aktuálny (kliknutý) odkaz ako aktívny
                document.querySelectorAll(`.nav-link[data-section="${targetSection}"]`).forEach(nl => nl.classList.add('active'));

                // Skryje všetky sekcie
                contentSections.forEach(section => {
                    section.style.display = 'none';
                });

                // Zobrazí len tú sekciu, ktorá bola vybraná
                const targetElement = document.getElementById(targetSection + '-section');
                if (targetElement) {
                    targetElement.style.display = 'block';

                    // Načíta dáta pre konkrétnu sekciu
                    loadSectionData(targetSection);
                }
            });
        });

        // Načíta úvodné štatistiky pre dashboard
        loadDashboardStats();
    });

    // Načíta štatistiky pre dashboard (napr. počet používateľov, kurzov, atď.)
    function loadDashboardStats() {
        fetch('admin_api.php?action=get_stats')
            .then(response => response.json())
            .then(data => {
                // Aktualizuje UI s údajmi alebo nastaví na '0' ak chýbajú
                document.getElementById('users-count').textContent = data.users || '0';
                document.getElementById('courses-count').textContent = data.courses || '0';
                document.getElementById('team-count').textContent = data.team || '0';
                document.getElementById('testimonials-count').textContent = data.testimonials || '0';
            })
            .catch(error => {
                console.log('Stats not available yet'); // Fallback ak API zlyhá
                // Nastavenie predvolených hodnôt
                document.getElementById('users-count').textContent = '1';
                document.getElementById('courses-count').textContent = '0';
                document.getElementById('team-count').textContent = '0';
                document.getElementById('testimonials-count').textContent = '0';
            });
    }

    // Na základe vybranej sekcie zavolá funkciu pre načítanie dát
    function loadSectionData(section) {
        switch(section) {
            case 'users':
                loadUsers();
                break;
            case 'courses':
                loadCourses();
                break;
            // Môžeš pridať ďalšie sekcie sem
        }
    }

    // Získa zoznam používateľov z API a zobrazí ich v tabuľke
    function loadUsers() {
        fetch('admin_api.php?action=get_users')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('users-table-body');
                tbody.innerHTML = ''; // Vymaže predošlé riadky

                data.forEach(user => {
                    // Pre každého používateľa sa vytvorí nový riadok
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
                    tbody.appendChild(row); // Pridá riadok do tabuľky
                });
            })
            .catch(error => {
                console.log('Users data not available yet');
                // Fallback výpis ak sa nepodarí načítať dáta
                document.getElementById('users-table-body').innerHTML =
                    '<tr><td colspan="5" class="text-center">Údaje o používateľoch nie sú k dispozícii</td></tr>';
            });
    }

    // Placeholder pre načítanie kurzov (momentálne len loguje)
    function loadCourses() {
        console.log('Loading courses...');
    }

    // Funkcia na úpravu údajov o používateľovi (používa prompt)
    function editUser(userId) {
        const row = event.target.closest('tr');
        const cells = row.cells;

        // Vyžiada si nové údaje cez prompt
        const username = prompt('Nové používateľské meno:', cells[1].textContent);
        const email = prompt('Nový email:', cells[2].textContent);
        const role = prompt('Nová rola (admin/user):', cells[3].querySelector('.badge').textContent);

        if (username && email && role) {
            // Príprava údajov na odoslanie
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('username', username);
            formData.append('email', email);
            formData.append('role', role);

            // Odoslanie požiadavky na aktualizáciu používateľa
            fetch('admin_api.php?action=update_user', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Používateľ bol aktualizovaný');
                        loadUsers(); // Refresh tabuľky
                    } else {
                        alert('Chyba: ' + (data.error || 'Neznáma chyba'));
                    }
                })
                .catch(error => {
                    alert('Chyba pri komunikácii so serverom');
                });
        }
    }

    // Funkcia na vymazanie používateľa
    function deleteUser(userId) {
        if (confirm('Naozaj chcete zmazať tohto používateľa?')) {
            const formData = new FormData();
            formData.append('user_id', userId);

            fetch('admin_api.php?action=delete_user', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Používateľ bol zmazaný');
                        loadUsers(); // Aktualizácia tabuľky
                    } else {
                        alert('Chyba: ' + (data.error || 'Neznáma chyba'));
                    }
                })
                .catch(error => {
                    alert('Chyba pri komunikácii so serverom');
                });
        }
    }

    // Odhlásenie používateľa
    document.getElementById('logoutBtn').addEventListener('click', () => {
        fetch('admin_api.php?action=logout')
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Presmerovanie na login stránku
                    window.location.href = '/ukf_project/scholar/index.php';
                } else {
                    alert('Chyba pri odhlasovaní');
                }
            })
            .catch(() => alert('Chyba pri odhlasovaní'));
    });
</script>
