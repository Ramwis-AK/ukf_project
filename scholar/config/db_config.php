<?php
/**
 * db_config.php
 * Singleton na bezpečné pripojenie k databáze a základné operácie.
 */

final class Database {
    private static ?Database $instance = null; // Jediná inštancia triedy (singleton - typ Database alebo Null)
    private PDO $connection;                   // PDO objekt pre DB pripojenie

    // Konfigurácia pripojenia k databáze
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'gorm_db';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    // Súkromný konštruktor, zabraňuje vytvoreniu viac inštancií (singleton)
    private function __construct() {
        // $dsn ako sa pripojiť k db; adresa servera, názov db, kódovania znakov
        $dsn = "mysql:host=".self::DB_HOST.";dbname=".self::DB_NAME.";charset=".self::DB_CHARSET;

        // PDO voľby pre správnu prácu s DB a chybami
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Vyhadzovanie výnimiek pri chybe
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Výsledky ako asociatívne pole | kluc-nazov stlpcu
            PDO::ATTR_EMULATE_PREPARES   => false,                     // spracuvava sa na strane db a nie v php
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::DB_CHARSET, // Nastavenie  medy aplikaciou a db
            PDO::ATTR_PERSISTENT         => false,                     // pripojenie sa ukonci vždy na konci skriptu
            PDO::ATTR_TIMEOUT            => 30,                        // PDO čaká na odpoveď max ??s
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

    // Zakáže klonovanie, aby sa nedali vytvoriť ďalšie inštancie
    private function __clone() {}

    // Zakáže obnove objektu z uložených dát (napr. z file, cache alebo session).
    public function __wakeup() {
        throw new Exception("Unserializácia nie je povolená.");
    }

    //Vráti jedinú inštanciu triedy (singleton)
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //Vráti PDO pripojenie pre priame použitie
    public function getConnection(): PDO {
        return $this->connection;
    }

    //Základný SELECT dotaz
    // public function select — verejná metóda triedy, prístupná zvonku.
    // string $query — prvý argument, SQL dotaz ako reťazec (napr. "SELECT * FROM users WHERE id = :id").
    // array $params = [] — druhý argument, pole parametrov na bezpečné dosadenie do SQL dotazu (defaultne prázdne).
    // : array|false — návratový typ, funkcia vráti buď pole (array) s výsledkami, alebo false pri chybe.

    public function select(string $query, array $params = []): array|false {
        try {
            $stmt = $this->connection->prepare($query); // Pripraví SQL dotaz (prevents SQL injection)
            $stmt->execute($params); // Spustí dotaz s parametrami
            return $stmt->fetchAll(); // Vráti všetky výsledky ako pole asociatívnych polí
        } catch (PDOException $e) {
            error_log("SELECT query error: " . $e->getMessage()); // Pri chybe zapíše do error logu
            return false;
        }
    }

    //Vloženie dát (INSERT).
    public function insert(string $query, array $params = []): int|false {
        try {
            $stmt = $this->connection->prepare($query);
            $success = $stmt->execute($params);
            return $success ? (int)$this->connection->lastInsertId() : false;  // vráti ID posledného vloženého záznamu, ako celé číslo alebo false
        } catch (PDOException $e) {
            error_log("INSERT query error: " . $e->getMessage());
            return false;
        }
    }

    //Aktualizácia dát (UPDATE)
    //zistuje koľko riadkov bolo ovplivnených
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

    //Vymazanie dát (DELETE).
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

    // Transakčné metódy - buď sa vykoná sada dotazov alebo ani jeden

    //Začiatok transakcie
    public function beginTransaction(): bool {
        return $this->connection->beginTransaction();
    }

    //Potvrdenie transakcie
    public function commit(): bool {
        return $this->connection->commit();
    }

    //Vrátenie transakcie (rollback)
    public function rollback(): bool {
        return $this->connection->rollBack();
    }

// Ak je vstup reťazec, odstráni nepotrebné medzery a zakóduje špeciálne HTML znaky, aby sa zobrazili ako bežný text
    public static function sanitizeInput(mixed $input): mixed {
        if (is_string($input)) {
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
        return $input;
    }

    //Overenie platnosti e-mailu
    public static function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    //Hashovanie hesla
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    //Overenie hesla voči uloženému hashu
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
