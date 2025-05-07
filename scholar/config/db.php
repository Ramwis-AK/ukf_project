<?php
/**
 * Database configuration
 *
 * @package Scholar
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'scholar_db');

/**
 * Create a database connection
 *
 * @return mysqli|false MySQLi connection object or false on failure
 */
function get_db_connection() {
    static $conn = null;

    // If connection already established, return it
    if ($conn !== null) {
        return $conn;
    }

    // Create new connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        return false;
    }

    // Set charset to UTF-8
    $conn->set_charset('utf8mb4');

    return $conn;
}

/**
 * Execute a query and return results
 *
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array|false Results as associative array or false on failure
 */
function db_query($sql, $params = []) {
    $conn = get_db_connection();

    if (!$conn) {
        return false;
    }

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Query preparation failed: " . $conn->error);
        return false;
    }

    // Bind parameters if any
    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
        }

        $bind_params = array_merge([$types], $params);
        $stmt->bind_param(...$bind_params);
    }

    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Query execution failed: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $result = $stmt->get_result();

    if (!$result) {
        // For INSERT, UPDATE, DELETE queries
        $stmt->close();
        return true;
    }

    // Fetch all results
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $data;
}

/**
 * Get a single row from the database
 *
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array|false First row as associative array or false on failure
 */
function db_get_row($sql, $params = []) {
    $results = db_query($sql, $params);

    if ($results === false || empty($results)) {
        return false;
    }

    return $results[0];
}

/**
 * Insert data into a table
 *
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @return int|false Last insert ID or false on failure
 */
function db_insert($table, $data) {
    $conn = get_db_connection();

    if (!$conn) {
        return false;
    }

    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Insert preparation failed: " . $conn->error);
        return false;
    }

    // Bind parameters
    $types = '';
    $values = [];

    foreach ($data as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_float($value)) {
            $types .= 'd';
        } elseif (is_string($value)) {
            $types .= 's';
        } else {
            $types .= 'b';
        }
        $values[] = $value;
    }

    $bind_params = array_merge([$types], $values);
    $stmt->bind_param(...$bind_params);

    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Insert execution failed: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $last_id = $conn->insert_id;
    $stmt->close();

    return $last_id;
}

/**
 * Update data in a table
 *
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @param string $where Where clause (without "WHERE")
 * @param array $where_params Parameters for where clause
 * @return bool True on success, false on failure
 */
function db_update($table, $data, $where, $where_params = []) {
    $conn = get_db_connection();

    if (!$conn) {
        return false;
    }

    $set_clauses = [];
    foreach (array_keys($data) as $column) {
        $set_clauses[] = "$column = ?";
    }

    $set_clause = implode(', ', $set_clauses);

    $sql = "UPDATE $table SET $set_clause WHERE $where";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Update preparation failed: " . $conn->error);
        return false;
    }

    // Bind parameters
    $types = '';
    $values = [];

    foreach ($data as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_float($value)) {
            $types .= 'd';
        } elseif (is_string($value)) {
            $types .= 's';
        } else {
            $types .= 'b';
        }
        $values[] = $value;
    }

    foreach ($where_params as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_float($value)) {
            $types .= 'd';
        } elseif (is_string($value)) {
            $types .= 's';
        } else {
            $types .= 'b';
        }
        $values[] = $value;
    }

    $bind_params = array_merge([$types], $values);
    $stmt->bind_param(...$bind_params);

    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Update execution failed: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $affected_rows = $stmt->affected_rows;
    $stmt->close();

    return $affected_rows > 0;
}