<?php

require_once __DIR__ . '/../config/db_config.php';

class User
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function sanitizeInput(string $input): string
    {
        return Database::sanitizeInput($input);
    }

    public function findByUsername(string $username): ?array
    {
        $result = $this->db->select("SELECT user_ID, username, password, role FROM users WHERE username = ?", [$username]);
        return $result[0] ?? null;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return Database::verifyPassword($password, $hash);
    }

    public function login(string $username, string $password): bool
    {
        $username = $this->sanitizeInput($username);
        $user = $this->findByUsername($username);

        if ($user && $this->verifyPassword($password, $user['password'])) {
            // Nastav session
            $_SESSION["loggedin"] = true;
            $_SESSION["user_id"] = $user['user_ID'];
            $_SESSION["username"] = $user['username'];
            $_SESSION["role"] = $user['role'];
            return true;
        }
        return false;
    }
}
