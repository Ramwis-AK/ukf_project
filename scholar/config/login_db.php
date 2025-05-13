<?php
// Database connection parameters
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gorm_db');

// Create connection
function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, login_gorm);

    // Check connection
    if ($conn->connect_error) {
        die("Pripojenie k databáze zlyhalo: " . $conn->connect_error);
    }

    // Set character set to utf8
    $conn->set_charset("utf8");

    return $conn;
}

// Close database connection
function closeDB($conn) {
    $conn->close();
}

// Initialize the database if it doesn't exist
function initializeDatabase() {
    // Connect to MySQL server without selecting a database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

    if ($conn->connect_error) {
        die("Pripojenie k MySQL serveru zlyhalo: " . $conn->connect_error);
    }

    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS " . login_gorm . " CHARACTER SET utf8 COLLATE utf8_general_ci";

    if ($conn->query($sql) === FALSE) {
        die("Vytvorenie databázy zlyhalo: " . $conn->error);
    }

    // Select the database
    $conn->select_db(login_gorm);

    // Create users table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    if ($conn->query($sql) === FALSE) {
        die("Vytvorenie tabuľky users zlyhalo: " . $conn->error);
    }

    $conn->close();
}

// Call this function when the website is first loaded
initializeDatabase();