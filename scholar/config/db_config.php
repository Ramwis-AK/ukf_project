<?php
/**
 * db_config.php
 * Singleton na bezpečné pripojenie k databáze a základné operácie.
 * Používaj všade vo svojom projekte.
 */

final class Database {
    private static ?Database $instance = null; // Jediná inštancia triedy (singleton)
    private PDO $connection;                   // PDO objekt pre DB pripojenie

    // Konfigurácia pripojenia k databáze
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'gorm_db';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    // Súkromný konštruktor, zabraňuje vytvoreniu viac inštancií (singleton)
    private function __construct() {
        // DSN (Data Source Name) reťazec pre PDO pripojenie
        $dsn = "mysql:host=".self::DB_HOST.";dbname=".self::DB_NAME.";charset=".self::DB_CHARSET;

        // PDO voľby pre správnu prácu s DB a chybami
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Vyhadzovanie výnimiek pri chybe
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Výsledky ako asociatívne pole
            PDO::ATTR_EMULATE_PREPARES   => false,                     // Použitie natívnych prepared statements
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::DB_CHARSET, // Nastavenie znakovania
            PDO::ATTR_PERSISTENT         => false,                     // Nepoužívame persist. pripojenie
            PDO::ATTR_TIMEOUT            => 30,                        // Timeout 30s
        ];

        try {
            // Vytvorenie PDO pripojenia
            $this->connection = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
        } catch (PDOException $e) {
            // Pri chybe zapíš do error logu a ukonči skript s chybovou správou
            error_log("Database connection error: " . $e->getMessage());
            die("Chyba pripojenia k databáze.");
        }
    }

    // Zakáž klonovanie, aby sa nedali vytvoriť ďalšie inštancie
    private function __clone() {}

    // Zakáž unserializáciu, aby sa singleton nezlomil
    public function __wakeup() {
        throw new Exception("Unserializácia nie je povolená.");
    }

    /**
     * Vráti jedinú inštanciu triedy (singleton)
     * @return Database
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Vráti PDO pripojenie pre priame použitie
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->connection;
    }

    /**
     * Základný SELECT dotaz.
     * Vráti pole výsledkov alebo false pri chybe.
     * @param string $query SQL dotaz s parametrami
     * @param array $params Parametre pre prepared statement
     * @return array|false
     */
    public function select(string $query, array $params = []): array|false {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("SELECT query error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vloženie dát (INSERT).
     * Vráti ID posledného vloženého riadku alebo false.
     * @param string $query
     * @param array $params
     * @return int|false
     */
    public function insert(string $query, array $params = []): int|false {
        try {
            $stmt = $this->connection->prepare($query);
            $success = $stmt->execute($params);
            return $success ? (int)$this->connection->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("INSERT query error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Aktualizácia dát (UPDATE).
     * Vráti počet upravených riadkov alebo false.
     * @param string $query
     * @param array $params
     * @return int|false
     */
    public function update(string $query, array $params = []): int|false {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("UPDATE query error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vymazanie dát (DELETE).
     * Vráti počet vymazaných riadkov alebo false.
     * @param string $query
     * @param array $params
     * @return int|false
     */
    public function delete(string $query, array $params = []): int|false {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("DELETE query error: " . $e->getMessage());
            return false;
        }
    }

    // Transakčné metódy na začatie, potvrdenie a vrátenie transakcie

    /**
     * Začiatok transakcie.
     * @return bool
     */
    public function beginTransaction(): bool {
        return $this->connection->beginTransaction();
    }

    /**
     * Potvrdenie transakcie.
     * @return bool
     */
    public function commit(): bool {
        return $this->connection->commit();
    }

    /**
     * Vrátenie transakcie (rollback).
     * @return bool
     */
    public function rollback(): bool {
        return $this->connection->rollBack();
    }

    /**
     * Sanitizácia vstupov - trim a htmlspecialchars len pre stringy.
     * @param mixed $input
     * @return mixed
     */
    public static function sanitizeInput(mixed $input): mixed {
        if (is_string($input)) {
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
        return $input;
    }

    /**
     * Overenie platnosti e-mailu.
     * @param string $email
     * @return bool
     */
    public static function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Hashovanie hesla bezpečným algoritmom.
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Overenie hesla voči uloženému hashu.
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
