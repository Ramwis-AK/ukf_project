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

// Nastav JSON header
header('Content-Type: application/json');

try {
    $db = Database::getInstance();

    switch ($action) {
        case 'get_stats':
            $stats = [];

            // Počet používateľov
            $result = $db->select("SELECT COUNT(*) as count FROM users");
            $stats['users'] = $result ? $result[0]['count'] : 0;

            // Počet kurzov
            $result = $db->select("SELECT COUNT(*) as count FROM courses");
            $stats['courses'] = $result ? $result[0]['count'] : 0;

            // Počet team členov
            $result = $db->select("SELECT COUNT(*) as count FROM team");
            $stats['team'] = $result ? $result[0]['count'] : 0;

            // Počet testimonials
            $result = $db->select("SELECT COUNT(*) as count FROM testimonials");
            $stats['testimonials'] = $result ? $result[0]['count'] : 0;

            echo json_encode($stats);
            break;

        case 'get_users':
            $users = $db->select("SELECT user_ID, username, email, role FROM users ORDER BY user_ID");
            echo json_encode($users ?: []);
            break;

        case 'delete_user':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = $_POST['user_id'] ?? 0;
                $currentUserId = $_SESSION['user_id'];

                // Zabráň zmazaniu vlastného účtu
                if ($userId == $currentUserId) {
                    echo json_encode(['error' => 'Nemôžete zmazať vlastný účet']);
                    break;
                }

                $result = $db->delete("DELETE FROM users WHERE user_ID = ?", [$userId]);

                if ($result) {
                    echo json_encode(['success' => 'Používateľ bol zmazaný']);
                } else {
                    echo json_encode(['error' => 'Chyba pri mazaní používateľa']);
                }
            }
            break;

        case 'update_user':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = $_POST['user_id'] ?? 0;
                $username = Database::sanitizeInput($_POST['username'] ?? '');
                $email = Database::sanitizeInput($_POST['email'] ?? '');
                $role = $_POST['role'] ?? 'user';

                // Validácia
                if (empty($username) || empty($email)) {
                    echo json_encode(['error' => 'Všetky polia sú povinné']);
                    break;
                }

                if (!Database::validateEmail($email)) {
                    echo json_encode(['error' => 'Neplatný email formát']);
                    break;
                }

                // Aktualizuj používateľa
                $result = $db->update(
                    "UPDATE users SET username = ?, email = ?, role = ? WHERE user_ID = ?",
                    [$username, $email, $role, $userId]
                );

                if ($result !== false) {
                    echo json_encode(['success' => 'Používateľ bol aktualizovaný']);
                } else {
                    echo json_encode(['error' => 'Chyba pri aktualizácii používateľa']);
                }
            }
            break;

        case 'add_user':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = Database::sanitizeInput($_POST['username'] ?? '');
                $email = Database::sanitizeInput($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? 'user';

                // Validácia
                if (empty($username) || empty($email) || empty($password)) {
                    echo json_encode(['error' => 'Všetky polia sú povinné']);
                    break;
                }

                if (!Database::validateEmail($email)) {
                    echo json_encode(['error' => 'Neplatný email formát']);
                    break;
                }

                if (strlen($password) < 6) {
                    echo json_encode(['error' => 'Heslo musí mať aspoň 6 znakov']);
                    break;
                }

                // Skontroluj duplicity
                $existing = $db->select("SELECT user_ID FROM users WHERE username = ? OR email = ?", [$username, $email]);
                if ($existing) {
                    echo json_encode(['error' => 'Používateľské meno alebo email už existuje']);
                    break;
                }

                // Zahashuj heslo a pridaj používateľa
                $hashedPassword = Database::hashPassword($password);
                $result = $db->insert(
                    "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)",
                    [$username, $email, $hashedPassword, $role]
                );

                if ($result) {
                    echo json_encode(['success' => 'Používateľ bol pridaný', 'user_id' => $result]);
                } else {
                    echo json_encode(['error' => 'Chyba pri pridávaní používateľa']);
                }
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