<?php

require_once __DIR__ . '/../config/db_config.php';

class User
{
    // Privátna premenná pre inštanciu databázy
    private Database $db;

    public function __construct()
    {
        // V konštruktore získavame singleton inštanciu databázy
        $this->db = Database::getInstance();
    }

    public function sanitizeInput(string $input): string
    {
        // Očistenie vstupu pomocou metódy z triedy Database
        return Database::sanitizeInput($input);
    }

    public function findByUsername(string $username): ?array
    {
        // Hľadá používateľa podľa užívateľského mena v databáze
        // Používame pripravený SQL dotaz s parametrom pre bezpečnosť
        $result = $this->db->select(
            "SELECT user_ID, username, password, role FROM users WHERE username = ?",
            [$username]
        );
        // Ak používateľ existuje, vráti jeho údaje (prvý záznam), inak null
        return $result[0] ?? null;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        // Overí heslo zadané používateľom voči zahashovanému heslu v databáze
        return Database::verifyPassword($password, $hash);
    }

    public function login(string $username, string $password): bool
    {
        // Prihlási používateľa s daným menom a heslom

        // Najprv očistí používateľské meno od nebezpečných znakov
        $username = $this->sanitizeInput($username);

        // Nájdi používateľa podľa mena
        $user = $this->findByUsername($username);

        // Ak používateľ existuje a heslo súhlasí s hashom v DB
        if ($user && $this->verifyPassword($password, $user['password'])) {
            // Nastaví potrebné údaje do session pre prihlásenie
            $_SESSION["loggedin"] = true;
            $_SESSION["user_id"] = $user['user_ID'];
            $_SESSION["username"] = $user['username'];
            $_SESSION["role"] = $user['role'];
            return true; // úspešné prihlásenie
        }

        return false; // neúspešné prihlásenie (nesprávne meno alebo heslo)
    }
}
