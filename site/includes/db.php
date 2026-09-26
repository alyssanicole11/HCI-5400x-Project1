<?php
/**
 * DATABASE HELPERS
 * ---------------------------------------------------------------
 * Every page talks to MySQL through these 4 small functions, so you
 * never have to remember the PDO details:
 *
 *   db_all($sql, $params)    -> array of rows        (SELECT many)
 *   db_one($sql, $params)    -> one row or null      (SELECT one)
 *   db_value($sql, $params)  -> a single value       (SELECT COUNT(*) ...)
 *   db_run($sql, $params)    -> runs INSERT/UPDATE/DELETE, returns # rows changed
 *
 * ALWAYS put user input in $params, never glue it into the SQL string.
 * The "?" placeholders are filled in safely (this prevents SQL injection):
 *
 *   $rows = db_all('SELECT * FROM titles WHERE year > ? AND media_type = ?', [2000, 'movie']);
 */

function db() {
    static $pdo = null;              // "static" = connect only once per page load
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errors throw exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // rows are ['column' => value]
                PDO::ATTR_EMULATE_PREPARES   => false,                  // real ints stay ints (rating 0 != '')
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo '<h1>Database connection failed</h1>';
            echo DEBUG ? '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>'
                       : '<p>Please try again later.</p>';
            exit;
        }
    }
    return $pdo;
}

function db_run($sql, $params = []) {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

function db_all($sql, $params = []) {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function db_one($sql, $params = []) {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
}

function db_value($sql, $params = []) {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $value = $stmt->fetchColumn();
    return $value === false ? null : $value;
}
