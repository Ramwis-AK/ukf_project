<?php
/**
 * Bezpečné pripojenie k databáze
 * Súbor: db_config.php
 */

// Zabráni priamemu prístupu k súboru
if (!defined('DB_ACCESS_ALLOWED')) {
    die('Priamy prístup nie je povolený');
}

class DatabaseConnection {
    private static $instance = null;
    private $connection;

    // Databázové konfiguračné údaje - ZMEŇTE PODĽA VAŠICH NASTAVENÍ
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'gorm_db';
    private const DB_USER = 'root';  // namiesto 'your_username'
    private const DB_PASS = '';      // namiesto 'your_password'
    private const DB_CHARSET = 'utf8mb4';

    private function __construct() {
        $this->connect();
    }

    /**
     * Singleton pattern - jedna inštancia pripojenia
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Vytvorenie bezpečného pripojenia k databáze
     */
    private function connect() {
        try {
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::DB_CHARSET,
                PDO::ATTR_PERSISTENT         => false,
                PDO::ATTR_TIMEOUT            => 30
            ];

            $this->connection = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);

        } catch (PDOException $e) {
            error_log("Chyba pripojenia k databáze: " . $e->getMessage());
            die("Chyba pripojenia k databáze. Skúste neskôr.");
        }
    }

    /**
     * Získanie pripojenia k databáze
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Bezpečné vykonanie SELECT dotazu
     */
    public function select($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Chyba SELECT dotazu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bezpečné vykonanie INSERT dotazu
     */
    public function insert($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $result = $stmt->execute($params);
            return $result ? $this->connection->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Chyba INSERT dotazu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bezpečné vykonanie UPDATE dotazu
     */
    public function update($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Chyba UPDATE dotazu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bezpečné vykonanie DELETE dotazu
     */
    public function delete($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Chyba DELETE dotazu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Začatie transakcie
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    /**
     * Potvrdenie transakcie
     */
    public function commit() {
        return $this->connection->commit();
    }

    /**
     * Zrušenie transakcie
     */
    public function rollback() {
        return $this->connection->rollback();
    }

    /**
     * Validácia a sanitizácia vstupných údajov
     */
    public static function sanitizeInput($input) {
        if (is_string($input)) {
            return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
        }
        return $input;
    }

    /**
     * Validácia číselných hodnôt
     */
    public static function validateInt($value, $min = null, $max = null) {
        $value = filter_var($value, FILTER_VALIDATE_INT);
        if ($value === false) return false;

        if ($min !== null && $value < $min) return false;
        if ($max !== null && $value > $max) return false;

        return $value;
    }

    /**
     * Validácia email adresy
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Hashovanie hesiel
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }

    /**
     * Overenie hesla
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Zatvorenie pripojenia
     */
    public function __destruct() {
        $this->connection = null;
    }

    // Zabránenie klonovaniu
    private function __clone() {}

    // Zabránenie unserialize
    public function __wakeup() {
        throw new Exception("Unserializácia nie je povolená");
    }
}

/**
 * Funkcia pre jednoduché získanie inštancie databázy
 */
function getDB() {
    return DatabaseConnection::getInstance();
}

/**
 * Funkcia pre bezpečné pripojenie k databáze na jednotlivých stránkach
 */
function connectToDatabase() {
    // Definovanie konštanty pre povolenie prístupu
    if (!defined('DB_ACCESS_ALLOWED')) {
        define('DB_ACCESS_ALLOWED', true);
    }

    return DatabaseConnection::getInstance();
}

// Nastavenie error reportingu pre produkčné prostredie
if (!defined('DEVELOPMENT_MODE')) {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);
}
?>}