<?php
// Načítaj databázové pripojenie
require_once __DIR__ . '/../config/db.php';

// Existujúce funkcie
function getBanners() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM banners");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFacts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM facts");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTeamMembers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM team");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTestimonials() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM testimonials");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEvents() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date DESC, rating DESC LIMIT 4");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCourseCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM course_categories ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCourses() {
    global $pdo;
    $stmt = $pdo->query("SELECT c.*, cc.filter FROM courses c LEFT JOIN course_categories cc ON c.category_id = cc.id ORDER BY c.id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Nové autentifikačné funkcie
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: Includes/login.php");
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: index.php");
        exit;
    }
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Funkcia na získanie aktuálneho užívateľa z databázy
function getUserById($userId) {
    try {
        global $pdo;
        $stmt = $pdo->prepare("SELECT user_ID, username, email, role FROM users WHERE user_ID = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting user: " . $e->getMessage());
        return null;
    }
}

// Funkcia na aktualizáciu posledného prihlásenia
function updateLastLogin($userId) {
    try {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_ID = ?");
        $stmt->execute([$userId]);
    } catch (Exception $e) {
        error_log("Error updating last login: " . $e->getMessage());
    }
}
?>