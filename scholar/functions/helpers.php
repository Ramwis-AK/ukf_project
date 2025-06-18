<?php
require_once __DIR__ . '/../config/db_config.php';

class Helpers {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // --- OBSAH WEBU ---

    public function getBanners(): array {
        return $this->db->select("SELECT * FROM banners");
    }

    public function getFacts(): array {
        return $this->db->select("SELECT * FROM facts");
    }

    public function updateFact(int $id, int $number, string $speed, string $text): bool {
        $id = Database::sanitizeInput($id);
        $number = Database::sanitizeInput($number);
        $speed = Database::sanitizeInput($speed);
        $text = Database::sanitizeInput($text);

        return $this->db->update(
            "UPDATE facts SET title = ?, description = ? WHERE id = ?",
            [$number, $speed, $text, $id]
        );
    }

    public function getTeamMembers(): array {
        return $this->db->select("SELECT * FROM team");
    }

    public function getTestimonials(): array {
        return $this->db->select("SELECT * FROM testimonials");
    }

    public function getEvents(): array {
        return $this->db->select("SELECT * FROM events ORDER BY date DESC, rating DESC LIMIT 4");
    }

    public function getCourseCategories(): array {
        return $this->db->select("SELECT * FROM course_categories ORDER BY id");
    }

    public function getCourses(): array {
        return $this->db->select("
            SELECT c.*, cc.filter 
            FROM courses c 
            LEFT JOIN course_categories cc ON c.category_id = cc.id 
            ORDER BY c.id
        ");
    }

    // --- AUTH METÓDY ---

    public static function isLoggedIn(): bool {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    public static function isAdmin(): bool {
        return self::isLoggedIn() && ($_SESSION['role'] ?? null) === 'admin';
    }

    public static function getCurrentUser(): ?array {
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
        if (!self::isLoggedIn()) {
            header("Location: Includes/login.php");
            exit;
        }
    }

    public static function requireAdmin(): void {
        if (!self::isAdmin()) {
            header("Location: index.php");
            exit;
        }
    }

    public static function generateCSRFToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken(string $token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // --- PRÁCA S UŽÍVATEĽMI ---

    public function getUserById(int|string $userId): ?array {
        $userId = Database::sanitizeInput($userId);
        $result = $this->db->select("SELECT user_ID, username, email, role FROM users WHERE user_ID = ?", [$userId]);
        return $result[0] ?? null;
    }

    public function updateLastLogin(int|string $userId): bool {
        $userId = Database::sanitizeInput($userId);
        return $this->db->update("UPDATE users SET last_login = NOW() WHERE user_ID = ?", [$userId]);
    }
}
