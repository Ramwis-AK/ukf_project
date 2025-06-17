<?php
require_once __DIR__ . '/../config/db.php';

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
    $stmt = $pdo->query(" SELECT * FROM events
        ORDER BY date DESC, rating DESC
        LIMIT 4
    ");
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
?>
