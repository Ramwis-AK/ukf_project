<?php
require_once __DIR__ . '/../config/db.php';

function getBanners() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM banners");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCourses() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
