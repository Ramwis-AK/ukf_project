<?php
// Admin API - admin_api.php
define('DB_ACCESS_ALLOWED', true); // Povolenie na použitie databázy (pravdepodobne kontrola v súbore db_config.php)
session_start(); // Spustenie session pre autentifikáciu používateľa

// Načítanie konfiguračných a pomocných funkcií
require_once 'config/db_config.php';
require_once 'functions/helpers.php';

// Načítanie akcie z query stringu (?action=...) alebo prázdny reťazec
$action = $_GET['action'] ?? '';

// Kontrola, či je používateľ prihlásený ako admin (okrem akcie 'logout')
if ($action !== 'logout' && (!Helpers::isLoggedIn() || !Helpers::isAdmin())) {
    http_response_code(403); // Vráti HTTP 403 Forbidden
    echo json_encode(['error' => 'Unauthorized']); // Chybové hlásenie
    exit;
}

try {
    $db = Database::getInstance(); // Inicializácia singleton pripojenia na databázu

    switch ($action) {

        // Vracia základné štatistiky z databázy: počet používateľov, kurzov, tímových členov a referencií
        case 'get_stats':
            $stats = [];
            $result = $db->select("SELECT COUNT(*) as count FROM users");
            $stats['users'] = $result ? $result[0]['count'] : 0;
            $result = $db->select("SELECT COUNT(*) as count FROM courses");
            $stats['courses'] = $result ? $result[0]['count'] : 0;
            $result = $db->select("SELECT COUNT(*) as count FROM team");
            $stats['team'] = $result ? $result[0]['count'] : 0;
            $result = $db->select("SELECT COUNT(*) as count FROM testimonials");
            $stats['testimonials'] = $result ? $result[0]['count'] : 0;
            echo json_encode($stats);
            break;

        // Získanie zoznamu všetkých používateľov
        case 'get_users':
            $users = $db->select("SELECT user_ID, username, email, role FROM users ORDER BY user_ID");
            echo json_encode($users ?: []); // Vráti prázdne pole, ak nie sú nájdené žiadne dáta
            break;

        // ---------- BANNERY ----------

        // Získanie všetkých bannerov
        case 'get_banners':
            $banners = $db->select("SELECT * FROM banners ORDER BY id DESC");
            echo json_encode($banners ?: []);
            break;

        // Pridanie nového banneru cez POST požiadavku
        case 'add_banner':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $item_class = Database::sanitizeInput($_POST['item_class'] ?? '');
                $title = Database::sanitizeInput($_POST['title'] ?? '');
                $image_path = Database::sanitizeInput($_POST['image_path'] ?? '');

                $result = $db->insert(
                    "INSERT INTO banners (item_class, title, image_path, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
                    [$item_class, $title, $image_path]
                );

                echo json_encode($result ? ['success' => 'Banner pridaný'] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        // Aktualizácia existujúceho banneru cez POST požiadavku
        case 'update_banner':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $item_class = Database::sanitizeInput($_POST['item_class'] ?? '');
                $title = Database::sanitizeInput($_POST['title'] ?? '');
                $image_path = Database::sanitizeInput($_POST['image_path'] ?? '');

                $result = $db->update(
                    "UPDATE banners SET item_class = ?, title = ?, image_path = ?, updated_at = NOW() WHERE id = ?",
                    [$item_class, $title, $image_path, $id]
                );

                echo json_encode($result !== false ? ['success' => 'Banner aktualizovaný'] : ['error' => 'Chyba pri aktualizácii']);
            }
            break;

        // Vymazanie banneru cez POST požiadavku
        case 'delete_banner':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM banners WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Banner zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        // ---------- KURZY ----------

        // Získanie všetkých kurzov
        case 'get_courses':
            $courses = $db->select("SELECT * FROM courses ORDER BY id DESC");
            echo json_encode($courses ?: []);
            break;

        // Pridanie nového kurzu
        case 'add_course':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $author = Database::sanitizeInput($_POST['author'] ?? '');
                $title = Database::sanitizeInput($_POST['title'] ?? '');
                $category_id = $_POST['category_id'] ?? 0;

                $result = $db->insert(
                    "INSERT INTO courses (image, author, title, category_id) VALUES (?, ?, ?, ?)",
                    [$image, $author, $title, $category_id]
                );

                echo json_encode($result ? ['success' => 'Kurz pridaný'] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        // Aktualizácia existujúceho kurzu
        case 'update_course':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $author = Database::sanitizeInput($_POST['author'] ?? '');
                $title = Database::sanitizeInput($_POST['title'] ?? '');
                $category_id = $_POST['category_id'] ?? 0;

                $result = $db->update(
                    "UPDATE courses SET image = ?, author = ?, title = ?, category_id = ? WHERE id = ?",
                    [$image, $author, $title, $category_id, $id]
                );

                echo json_encode($result !== false ? ['success' => 'Kurz aktualizovaný'] : ['error' => 'Chyba pri aktualizácii']);
            }
            break;

        // Vymazanie kurzu
        case 'delete_course':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM courses WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Kurz zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        // ---------- FAKTY ----------

        // Získanie všetkých faktov
        case 'get_facts':
            $facts = $db->select("SELECT * FROM facts ORDER BY id DESC");
            echo json_encode($facts ?: []);
            break;

        // Pridanie nového faktu
        case 'add_fact':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $number = Database::sanitizeInput($_POST['number'] ?? '');
                $speed = Database::sanitizeInput($_POST['speed'] ?? '');
                $text = Database::sanitizeInput($_POST['text'] ?? '');
                $class = Database::sanitizeInput($_POST['class'] ?? '');

                $result = $db->insert(
                    "INSERT INTO facts (number, speed, text, class) VALUES (?, ?, ?, ?)",
                    [$number, $speed, $text, $class]
                );

                echo json_encode($result ? ['success' => 'Fakt pridaný'] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        // Aktualizácia existujúceho faktu
        case 'update_fact':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $number = Database::sanitizeInput($_POST['number'] ?? '');
                $speed = Database::sanitizeInput($_POST['speed'] ?? '');
                $text = Database::sanitizeInput($_POST['text'] ?? '');
                $class = Database::sanitizeInput($_POST['class'] ?? '');

                $result = $db->update(
                    "UPDATE facts SET number = ?, speed = ?, text = ?, class = ? WHERE id = ?",
                    [$number, $speed, $text, $class, $id]
                );

                echo json_encode($result !== false ? ['success' => 'Fakt aktualizovaný'] : ['error' => 'Chyba pri aktualizácii']);
            }
            break;

        // Vymazanie faktu
        case 'delete_fact':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM facts WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Fakt zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;
// ===== TÍM - CRUD operácie =====

        case 'get_team':
            // Načíta všetkých členov tímu z DB a zoradí ich podľa ID zostupne (najnovší hore)
            $team = $db->select("SELECT * FROM team ORDER BY id DESC");
            echo json_encode($team ?: []); // Ak je výsledok null/false, vráti prázdne pole
            break;

        case 'add_team':
            // Pridanie nového člena tímu (iba cez POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Získa a očistí vstupné údaje
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $category = Database::sanitizeInput($_POST['category'] ?? '');
                $name = Database::sanitizeInput($_POST['name'] ?? '');
                $facebook = Database::sanitizeInput($_POST['facebook'] ?? '');
                $twitter = Database::sanitizeInput($_POST['twitter'] ?? '');
                $linkedin = Database::sanitizeInput($_POST['linkedin'] ?? '');

                // Vloží člena do databázy
                $result = $db->insert(
                    "INSERT INTO team (image, category, name, facebook, twitter, linkedin) VALUES (?, ?, ?, ?, ?, ?)",
                    [$image, $category, $name, $facebook, $twitter, $linkedin]
                );

                echo json_encode($result ? ['success' => 'Člen tímu pridaný'] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        case 'update_team':
            // Úprava údajov existujúceho člena tímu (cez POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $category = Database::sanitizeInput($_POST['category'] ?? '');
                $name = Database::sanitizeInput($_POST['name'] ?? '');
                $facebook = Database::sanitizeInput($_POST['facebook'] ?? '');
                $twitter = Database::sanitizeInput($_POST['twitter'] ?? '');
                $linkedin = Database::sanitizeInput($_POST['linkedin'] ?? '');

                $result = $db->update(
                    "UPDATE team SET image = ?, category = ?, name = ?, facebook = ?, twitter = ?, linkedin = ? WHERE id = ?",
                    [$image, $category, $name, $facebook, $twitter, $linkedin, $id]
                );

                echo json_encode($result !== false ? ['success' => 'Člen tímu aktualizovaný'] : ['error' => 'Chyba pri aktualizácii']);
            }
            break;

        case 'delete_team':
            // Vymazanie člena tímu podľa ID (POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM team WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Člen tímu zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

// ===== KOMENTÁRE (testimonials) - CRUD =====

        case 'get_testimonials':
            // Načíta všetky komentáre (napr. od zákazníkov) zoradené od najnovšieho
            $testimonials = $db->select("SELECT * FROM testimonials ORDER BY id DESC");
            echo json_encode($testimonials ?: []);
            break;

        case 'add_testimonial':
            // Pridanie komentára (cez POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $quote = Database::sanitizeInput($_POST['quote'] ?? '');
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $category = Database::sanitizeInput($_POST['category'] ?? '');
                $name = Database::sanitizeInput($_POST['name'] ?? '');

                $result = $db->insert(
                    "INSERT INTO testimonials (quote, image, category, name) VALUES (?, ?, ?, ?)",
                    [$quote, $image, $category, $name]
                );

                echo json_encode($result ? ['success' => 'Komentár pridaný'] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        case 'update_testimonial':
            // Aktualizácia komentára (cez POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $quote = Database::sanitizeInput($_POST['quote'] ?? '');
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $category = Database::sanitizeInput($_POST['category'] ?? '');
                $name = Database::sanitizeInput($_POST['name'] ?? '');

                $result = $db->update(
                    "UPDATE testimonials SET quote = ?, image = ?, category = ?, name = ? WHERE id = ?",
                    [$quote, $image, $category, $name, $id]
                );

                echo json_encode($result !== false ? ['success' => 'Komentár aktualizovaný'] : ['error' => 'Chyba pri aktualizácii']);
            }
            break;

        case 'delete_testimonial':
            // Vymazanie komentára podľa ID (cez POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM testimonials WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Komentár zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

// ===== GALÉRIA / EVENTS - CRUD =====

        case 'get_events':
            // Načíta všetky eventy (udalosti) z databázy
            $events = $db->select("SELECT * FROM events ORDER BY id DESC");
            echo json_encode($events ?: []);
            break;

        case 'add_event':
            // Pridanie nového eventu (POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $category = Database::sanitizeInput($_POST['category'] ?? '');
                $title = Database::sanitizeInput($_POST['title'] ?? '');
                $date = Database::sanitizeInput($_POST['date'] ?? '');
                $custumers = Database::sanitizeInput($_POST['custumers'] ?? ''); // pravdepodobne preklep, má byť customers?
                $rating = Database::sanitizeInput($_POST['rating'] ?? '');

                $result = $db->insert(
                    "INSERT INTO events (image, category, title, date, custumers, rating) VALUES (?, ?, ?, ?, ?, ?)",
                    [$image, $category, $title, $date, $custumers, $rating]
                );

                echo json_encode($result ? ['success' => 'Event pridaný'] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        case 'update_event':
            // Úprava eventu (POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $category = Database::sanitizeInput($_POST['category'] ?? '');
                $title = Database::sanitizeInput($_POST['title'] ?? '');
                $date = Database::sanitizeInput($_POST['date'] ?? '');
                $custumers = Database::sanitizeInput($_POST['custumers'] ?? '');
                $rating = Database::sanitizeInput($_POST['rating'] ?? '');

                $result = $db->update(
                    "UPDATE events SET image = ?, category = ?, title = ?, date = ?, custumers = ?, rating = ? WHERE id = ?",
                    [$image, $category, $title, $date, $custumers, $rating, $id]
                );

                echo json_encode($result !== false ? ['success' => 'Event aktualizovaný'] : ['error' => 'Chyba pri aktualizácii']);
            }
            break;

        case 'delete_event':
            // Vymazanie eventu (POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM events WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Event zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

// ===== OPERÁCIE NA POUŽÍVATEĽOCH =====

        case 'delete_user':
            // Vymazanie používateľa podľa ID (POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = (int)($_POST['user_id'] ?? 0);
                $currentUserId = $_SESSION['user_id'];

                // Zabráni zmazaniu samého seba
                if ($userId === $currentUserId) {
                    echo json_encode(['error' => 'Nemôžete zmazať vlastný účet']);
                    break;
                }

                // Pokus o vymazanie používateľa
                $deletedRows = $db->delete("DELETE FROM users WHERE user_ID = ?", [$userId]);

                if ($deletedRows === false) {
                    echo json_encode(['error' => 'Chyba pri mazaní používateľa']);
                } elseif ($deletedRows === 0) {
                    echo json_encode(['error' => 'Používateľ nebol nájdený']);
                } else {
                    echo json_encode(['success' => 'Používateľ bol úspešne zmazaný']);
                }
            } else {
                echo json_encode(['error' => 'Nepovolená metoda']);
            }
            break;

        case 'update_user':
            // Aktualizácia údajov používateľa (POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = $_POST['user_id'] ?? 0;
                $username = Database::sanitizeInput($_POST['username'] ?? '');
                $email = Database::sanitizeInput($_POST['email'] ?? '');
                $role = $_POST['role'] ?? 'user';

                // Overenie, že sú vyplnené povinné údaje
                if (empty($username) || empty($email)) {
                    echo json_encode(['error' => 'Všetky polia sú povinné']);
                    break;
                }

                // Overenie formátu e-mailu
                if (!Database::validateEmail($email)) {
                    echo json_encode(['error' => 'Neplatný email formát']);
                    break;
                }

                $result = $db->update(
                    "UPDATE users SET username = ?, email = ?, role = ? WHERE user_ID = ?",
                    [$username, $email, $role, $userId]
                );

                echo json_encode($result !== false ? ['success' => 'Používateľ aktualizovaný'] : ['error' => 'Chyba pri aktualizácii']);
            }
            break;
        case 'add_user':
            // Kontrolujeme, či bola požiadavka odoslaná cez POST metódu (bezpečnejšie pre dáta)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Načítame vstupné dáta z formulára, použijeme sanitáciu na ochranu proti útokom (napr. SQL Injection)
                $username = Database::sanitizeInput($_POST['username'] ?? '');
                $email = Database::sanitizeInput($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';  // Heslo nesanitujeme, môže obsahovať špeciálne znaky
                $role = $_POST['role'] ?? 'user';      // Ak nie je zadaná rola, nastavíme defaultnú "user"

                // Overenie, či niektoré z povinných polí nie je prázdne
                if (empty($username) || empty($email) || empty($password)) {
                    // Ak áno, pošleme späť chybovú správu v JSON formáte a ukončíme spracovanie
                    echo json_encode(['error' => 'Všetky polia sú povinné']);
                    break;
                }

                // Validujeme formát emailu – aby bol legitímny
                if (!Database::validateEmail($email)) {
                    echo json_encode(['error' => 'Neplatný email formát']);
                    break;
                }

                // Overujeme minimálnu dĺžku hesla, nastavenej na 8 znakov kvôli bezpečnosti
                if (strlen($password) < 8) {
                    echo json_encode(['error' => 'Heslo musí mať aspoň 8 znakov']);
                    break;
                }

                // Kontrola, či už neexistuje používateľ s rovnakým menom alebo emailom v databáze
                $existing = $db->select("SELECT user_ID FROM users WHERE username = ? OR email = ?", [$username, $email]);
                if ($existing) {
                    echo json_encode(['error' => 'Používateľské meno alebo email už existuje']);
                    break;
                }

                // Hashujeme heslo (zabezpečenie hesla pred uložením do DB)
                $hashedPassword = Database::hashPassword($password);

                // Vkladáme nového používateľa do tabuľky users
                $result = $db->insert(
                    "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)",
                    [$username, $email, $hashedPassword, $role]
                );

                // Odošleme späť úspešnú správu s ID nového používateľa alebo chybu, ak vloženie zlyhalo
                echo json_encode($result ? ['success' => 'Používateľ pridaný', 'user_id' => $result] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        case 'logout':
            // Odstránime všetky údaje zo session (odhlásenie používateľa)
            session_unset();
            // Zrušíme session, aby sa úplne vymazala
            session_destroy();
            // Odošleme späť úspešnú správu o odhlásení
            echo json_encode(['success' => 'Odhlásenie úspešné']);
            break;

        default:
            // Ak je požadovaná akcia neznáma alebo nepodporovaná, vrátime chybu
            echo json_encode(['error' => 'Neznáma akcia']);
            break;
    }

} catch (Exception $e) {
    // V prípade výnimky zalogujeme chybu do systémového logu
    error_log("Admin API Error: " . $e->getMessage());
    // Upozorníme používateľa na systémovú chybu bez zverejňovania detailov
    echo json_encode(['error' => 'Systémová chyba']);
}
?>
