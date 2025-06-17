<?php
/**
 * db_config.php
 * Singleton na bezpečné pripojenie k databáze a základné operácie.
 * Používaj všade vo svojom projekte.
 */

final class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    // Konfigurácia DB
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'gorm_db';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    // Súkromný konštruktor (singleton)
    private function __construct() {
        $dsn = "mysql:host=".self::DB_HOST.";dbname=".self::DB_NAME.";charset=".self::DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::DB_CHARSET,
            PDO::ATTR_PERSISTENT         => false,
            PDO::ATTR_TIMEOUT            => 30,
        ];

        try {
            $this->connection = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Chyba pripojenia k databáze.");
        }
    }

    // Zakáž klonovanie a unserializáciu
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Unserializácia nie je povolená.");
    }

    // Vráti inštanciu singletonu
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Vráti PDO pripojenie
    public function getConnection(): PDO {
        return $this->connection;
    }

    // Základný SELECT - vráti pole výsledkov alebo false pri chybe
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

    // Vloženie - vráti posledné insert ID alebo false
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

    // Update - vráti počet upravených riadkov alebo false
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

    // Delete - vráti počet vymazaných riadkov alebo false
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

    // Transakcie
    public function beginTransaction(): bool {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool {
        return $this->connection->commit();
    }

    public function rollback(): bool {
        return $this->connection->rollBack();
    }

    // Sanitizácia vstupov - trim + htmlspecialchars (len stringy)
    public static function sanitizeInput(mixed $input): mixed {
        if (is_string($input)) {
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
        return $input;
    }

    // Overenie emailu
    public static function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Hashovanie hesla (bezpečný default algoritmus)
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Overenie hesla
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
