<?php
require_once __DIR__ . '/../config/db_config.php';
// Načítanie súboru s konfiguráciou databázy a triedou Database

class Helpers {
    private Database $db;
    // Privátna premenná, ktorá bude obsahovať inštanciu databázy

    public function __construct() {
        $this->db = Database::getInstance();
        // V konštruktore získavame inštanciu databázy (singleton)
    }

    // --- OBSAH WEBU ---

    public function getBanners(): array {
        // Vráti všetky záznamy z tabuľky banners
        return $this->db->select("SELECT * FROM banners");
    }

    public function getFacts(): array {
        // Vráti všetky záznamy z tabuľky facts
        return $this->db->select("SELECT * FROM facts");
    }

    public function updateFact(int $id, int $number, string $speed, string $text): bool {
        // Aktualizuje záznam v tabuľke facts podľa id

        // Očistenie vstupov pre bezpečnosť (napr. pred SQL injection)
        $id = Database::sanitizeInput($id);
        $number = Database::sanitizeInput($number);
        $speed = Database::sanitizeInput($speed);
        $text = Database::sanitizeInput($text);

        // Spustenie update SQL dotazu s parametrami
        return $this->db->update(
            "UPDATE facts SET title = ?, speed = ?, text = ? WHERE id = ?",
            [$number, $speed, $text, $id]
        );
    }

    public function getTeamMembers(): array {
        // Vráti všetky záznamy z tabuľky team (členovia tímu)
        return $this->db->select("SELECT * FROM team");
    }

    public function getTestimonials(): array {
        // Vráti všetky záznamy z tabuľky testimonials (referencie)
        return $this->db->select("SELECT * FROM testimonials");
    }

    public function getEvents(): array {
        // Vráti najnovšie 4 udalosti zoradené podľa dátumu a hodnotenia zostupne
        return $this->db->select("SELECT * FROM events ORDER BY date DESC, rating DESC LIMIT 4");
    }

    public function getCourseCategories(): array {
        // Vráti všetky kategórie kurzov zoradené podľa id
        return $this->db->select("SELECT * FROM course_categories ORDER BY id");
    }

    public function getCourses(): array {
        // Vráti kurzy spoločne s ich kategóriou (pripojenie cez LEFT JOIN)
        return $this->db->select("
            SELECT c.*, cc.filter 
            FROM courses c 
            LEFT JOIN course_categories cc ON c.category_id = cc.id 
            ORDER BY c.id
        ");
    }

    // --- AUTH METÓDY (autentifikácia) ---

    public static function isLoggedIn(): bool {
        // Kontrola, či je používateľ prihlásený (session 'loggedin' true)
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    public static function isAdmin(): bool {
        // Kontrola, či je používateľ prihlásený a má rolu admin
        return self::isLoggedIn() && ($_SESSION['role'] ?? null) === 'admin';
    }

    public static function getCurrentUser(): ?array {
        // Vráti údaje o aktuálnom používateľovi alebo null, ak nie je prihlásený
        if (self::isLoggedIn()) {
            return [
                'user_id' => $_SESSION['user_id'] ?? null,
                'username' => $_SESSION['username'] ?? null,
                'role' => $_SESSION['role'] ?? null
            ];
        }
        return null;
    }

    public static function requireLogin(): void {
        // Ak používateľ nie je prihlásený, presmeruje na prihlasovaciu stránku a ukončí skript
        if (!self::isLoggedIn()) {
            header("Location: Includes/login.php");
            exit;
        }
    }

    public static function requireAdmin(): void {
        // Ak používateľ nie je admin, presmeruje na domovskú stránku a ukončí skript
        if (!self::isAdmin()) {
            header("Location: index.php");
            exit;
        }
    }

    public static function generateCSRFToken(): string {
        // Vygeneruje alebo vráti existujúci CSRF token pre ochranu formulárov pred útokmi
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // Bezpečný náhodný token, 64 znakov v hex formáte
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken(string $token): bool {
        // Porovná odovzdaný token s tým v session bezpečnou metódou hash_equals
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // --- PRÁCA S UŽÍVATEĽMI ---

    public function getUserById(int|string $userId): ?array {
        // Získanie používateľa podľa ID z tabuľky users

        $userId = Database::sanitizeInput($userId); // Očistenie vstupu
        $result = $this->db->select("SELECT user_ID, username, email, role FROM users WHERE user_ID = ?", [$userId]);
        return $result[0] ?? null; // Ak existuje, vráti prvý záznam, inak null
    }

    public function updateLastLogin(int|string $userId): bool {
        // Aktualizuje dátum a čas posledného prihlásenia používateľa na aktuálny čas
        $userId = Database::sanitizeInput($userId);
        return $this->db->update("UPDATE users SET last_login = NOW() WHERE user_ID = ?", [$userId]);
    }
}
