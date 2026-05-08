<?php
// ============================================================
//  config/db.php  —  Procedural MySQL Connection
//  Edit the constants below to match your environment
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // your MySQL username
define('DB_PASS', '');           // your MySQL password
define('DB_NAME', 'university_library');

/**
 * Returns a procedural mysqli connection (or dies with an error message).
 *
 * @return mysqli
 */
function getConnection(): mysqli
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$conn) {
        http_response_code(500);
        die(json_encode([
            'success' => false,
            'message' => 'Database connection failed: ' . mysqli_connect_error()
        ]));
    }

    mysqli_set_charset($conn, 'utf8mb4');
    return $conn;
}
