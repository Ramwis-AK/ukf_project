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
function getCourses() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM courses");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
