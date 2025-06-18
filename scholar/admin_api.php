<?php
// Admin API - admin_api.php
define('DB_ACCESS_ALLOWED', true);
session_start();

// Include potrebné súbory
require_once 'config/db_config.php';
require_once 'functions/helpers.php';

// Skontroluj admin práva (okrem logout akcie)
$action = $_GET['action'] ?? '';
if ($action !== 'logout' && (!Helpers::isLoggedIn() || !Helpers::isAdmin())) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $db = Database::getInstance();

    switch ($action) {
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

        case 'get_users':
            $users = $db->select("SELECT user_ID, username, email, role FROM users ORDER BY user_ID");
            echo json_encode($users ?: []);
            break;

        // BANNERY CRUD
        case 'get_banners':
            $banners = $db->select("SELECT * FROM banners ORDER BY id DESC");
            echo json_encode($banners ?: []);
            break;

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

        case 'delete_banner':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM banners WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Banner zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        // KURZY CRUD
        case 'get_courses':
            $courses = $db->select("SELECT * FROM courses ORDER BY id DESC");
            echo json_encode($courses ?: []);
            break;

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

        case 'delete_course':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM courses WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Kurz zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        // FAKTY CRUD
        case 'get_facts':
            $facts = $db->select("SELECT * FROM facts ORDER BY id DESC");
            echo json_encode($facts ?: []);
            break;

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

        case 'delete_fact':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM facts WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Fakt zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        // TÍM CRUD
        case 'get_team':
            $team = $db->select("SELECT * FROM team ORDER BY id DESC");
            echo json_encode($team ?: []);
            break;

        case 'add_team':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $category = Database::sanitizeInput($_POST['category'] ?? '');
                $name = Database::sanitizeInput($_POST['name'] ?? '');
                $facebook = Database::sanitizeInput($_POST['facebook'] ?? '');
                $twitter = Database::sanitizeInput($_POST['twitter'] ?? '');
                $linkedin = Database::sanitizeInput($_POST['linkedin'] ?? '');

                $result = $db->insert(
                    "INSERT INTO team (image, category, name, facebook, twitter, linkedin) VALUES (?, ?, ?, ?, ?, ?)",
                    [$image, $category, $name, $facebook, $twitter, $linkedin]
                );

                echo json_encode($result ? ['success' => 'Člen tímu pridaný'] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        case 'update_team':
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM team WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Člen tímu zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        // KOMENTÁRE CRUD
        case 'get_testimonials':
            $testimonials = $db->select("SELECT * FROM testimonials ORDER BY id DESC");
            echo json_encode($testimonials ?: []);
            break;

        case 'add_testimonial':
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM testimonials WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Komentár zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        // GALÉRIA CRUD
        case 'get_events':
            $events = $db->select("SELECT * FROM events ORDER BY id DESC");
            echo json_encode($events ?: []);
            break;

        case 'add_event':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $image = Database::sanitizeInput($_POST['image'] ?? '');
                $category = Database::sanitizeInput($_POST['category'] ?? '');
                $title = Database::sanitizeInput($_POST['title'] ?? '');
                $date = Database::sanitizeInput($_POST['date'] ?? '');
                $custumers = Database::sanitizeInput($_POST['custumers'] ?? '');
                $rating = Database::sanitizeInput($_POST['rating'] ?? '');

                $result = $db->insert(
                    "INSERT INTO events (image, category, title, date, custumers, rating) VALUES (?, ?, ?, ?, ?, ?)",
                    [$image, $category, $title, $date, $custumers, $rating]
                );

                echo json_encode($result ? ['success' => 'Event pridaný'] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        case 'update_event':
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? 0;
                $result = $db->delete("DELETE FROM events WHERE id = ?", [$id]);
                echo json_encode($result ? ['success' => 'Event zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        // Existujúce user operácie
        case 'delete_user':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = (int)($_POST['user_id'] ?? 0);
                $currentUserId = $_SESSION['user_id'];

                if ($userId === $currentUserId) {
                    echo json_encode(['error' => 'Nemôžete zmazať vlastný účet']);
                    break;
                }

                $result = $db->delete("DELETE FROM users WHERE user_ID = ?", [$userId]);
                echo json_encode($result ? ['success' => 'Používateľ zmazaný'] : ['error' => 'Chyba pri mazaní']);
            }
            break;

        case 'update_user':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = $_POST['user_id'] ?? 0;
                $username = Database::sanitizeInput($_POST['username'] ?? '');
                $email = Database::sanitizeInput($_POST['email'] ?? '');
                $role = $_POST['role'] ?? 'user';

                if (empty($username) || empty($email)) {
                    echo json_encode(['error' => 'Všetky polia sú povinné']);
                    break;
                }

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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = Database::sanitizeInput($_POST['username'] ?? '');
                $email = Database::sanitizeInput($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? 'user';

                if (empty($username) || empty($email) || empty($password)) {
                    echo json_encode(['error' => 'Všetky polia sú povinné']);
                    break;
                }

                if (!Database::validateEmail($email)) {
                    echo json_encode(['error' => 'Neplatný email formát']);
                    break;
                }

                if (strlen($password) < 8) {
                    echo json_encode(['error' => 'Heslo musí mať aspoň 8 znakov']);
                    break;
                }

                $existing = $db->select("SELECT user_ID FROM users WHERE username = ? OR email = ?", [$username, $email]);
                if ($existing) {
                    echo json_encode(['error' => 'Používateľské meno alebo email už existuje']);
                    break;
                }

                $hashedPassword = Database::hashPassword($password);
                $result = $db->insert(
                    "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)",
                    [$username, $email, $hashedPassword, $role]
                );

                echo json_encode($result ? ['success' => 'Používateľ pridaný', 'user_id' => $result] : ['error' => 'Chyba pri pridávaní']);
            }
            break;

        case 'logout':
            session_unset();
            session_destroy();
            echo json_encode(['success' => 'Odhlásenie úspešné']);
            break;

        default:
            echo json_encode(['error' => 'Neznáma akcia']);
            break;
    }

} catch (Exception $e) {
    error_log("Admin API Error: " . $e->getMessage());
    echo json_encode(['error' => 'Systémová chyba']);
}
?>