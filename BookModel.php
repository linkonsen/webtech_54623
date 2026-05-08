<?php
// ============================================================
//  model/BookModel.php  —  MODEL LAYER
//
//  Contains only database interaction functions.
//  No business logic, no output — pure data access.
// ============================================================

require_once __DIR__ . '/../config/db.php';


// ─────────────────────────────────────────
//  CREATE
// ─────────────────────────────────────────

/**
 * Insert a new book record into the database.
 *
 * @param  string $title
 * @param  string $author
 * @param  string $category
 * @param  string $status   'available' | 'borrowed' | 'reserved'
 * @return array  ['success' => bool, 'id' => int|null, 'message' => string]
 */
function insertBook(string $title, string $author, string $category, string $status): array
{
    $conn = getConnection();

    $sql  = "INSERT INTO books (title, author, category, status) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        mysqli_close($conn);
        return ['success' => false, 'id' => null, 'message' => 'Prepare failed: ' . mysqli_error($conn)];
    }

    mysqli_stmt_bind_param($stmt, 'ssss', $title, $author, $category, $status);

    if (mysqli_stmt_execute($stmt)) {
        $newId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return ['success' => true, 'id' => $newId, 'message' => 'Book added successfully.'];
    }

    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'id' => null, 'message' => 'Insert failed: ' . $err];
}


// ─────────────────────────────────────────
//  READ — all books
// ─────────────────────────────────────────

/**
 * Retrieve all books, optionally filtered by a search term.
 *
 * @param  string $search  Optional title/author search string
 * @return array  ['success' => bool, 'data' => array, 'message' => string]
 */
function getAllBooks(string $search = ''): array
{
    $conn = getConnection();

    if ($search !== '') {
        $sql  = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY added_at DESC";
        $stmt = mysqli_prepare($conn, $sql);
        $like = '%' . $search . '%';
        mysqli_stmt_bind_param($stmt, 'ss', $like, $like);
    } else {
        $sql  = "SELECT * FROM books ORDER BY added_at DESC";
        $stmt = mysqli_prepare($conn, $sql);
    }

    if (!$stmt) {
        mysqli_close($conn);
        return ['success' => false, 'data' => [], 'message' => 'Prepare failed.'];
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $books  = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return ['success' => true, 'data' => $books, 'message' => count($books) . ' record(s) found.'];
}


// ─────────────────────────────────────────
//  READ — single book by ID
// ─────────────────────────────────────────

/**
 * Retrieve one book by its primary key.
 *
 * @param  int  $id
 * @return array  ['success' => bool, 'data' => array|null, 'message' => string]
 */
function getBookById(int $id): array
{
    $conn = getConnection();

    $sql  = "SELECT * FROM books WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        mysqli_close($conn);
        return ['success' => false, 'data' => null, 'message' => 'Prepare failed.'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book   = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if ($book) {
        return ['success' => true, 'data' => $book, 'message' => 'Book found.'];
    }
    return ['success' => false, 'data' => null, 'message' => 'Book not found.'];
}


// ─────────────────────────────────────────
//  UPDATE
// ─────────────────────────────────────────

/**
 * Update all fields of an existing book record.
 *
 * @param  int    $id
 * @param  string $title
 * @param  string $author
 * @param  string $category
 * @param  string $status
 * @return array  ['success' => bool, 'message' => string]
 */
function updateBook(int $id, string $title, string $author, string $category, string $status): array
{
    $conn = getConnection();

    $sql  = "UPDATE books SET title = ?, author = ?, category = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        mysqli_close($conn);
        return ['success' => false, 'message' => 'Prepare failed: ' . mysqli_error($conn)];
    }

    mysqli_stmt_bind_param($stmt, 'ssssi', $title, $author, $category, $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if ($affected > 0) {
            return ['success' => true, 'message' => 'Book updated successfully.'];
        }
        return ['success' => false, 'message' => 'No changes made (record may not exist).'];
    }

    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Update failed: ' . $err];
}


// ─────────────────────────────────────────
//  DELETE
// ─────────────────────────────────────────

/**
 * Delete a book record by its primary key.
 *
 * @param  int  $id
 * @return array  ['success' => bool, 'message' => string]
 */
function deleteBook(int $id): array
{
    $conn = getConnection();

    $sql  = "DELETE FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        mysqli_close($conn);
        return ['success' => false, 'message' => 'Prepare failed.'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if ($affected > 0) {
            return ['success' => true, 'message' => 'Book deleted successfully.'];
        }
        return ['success' => false, 'message' => 'Book not found.'];
    }

    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Delete failed: ' . $err];
}


// ─────────────────────────────────────────
//  STATS (dashboard counters)
// ─────────────────────────────────────────

/**
 * Return aggregate counts grouped by availability status.
 *
 * @return array  ['success' => bool, 'data' => array]
 */
function getBookStats(): array
{
    $conn   = getConnection();
    $result = mysqli_query($conn, "SELECT status, COUNT(*) AS cnt FROM books GROUP BY status");

    $stats = ['total' => 0, 'available' => 0, 'borrowed' => 0, 'reserved' => 0];

    while ($row = mysqli_fetch_assoc($result)) {
        $stats[$row['status']] = (int)$row['cnt'];
        $stats['total']       += (int)$row['cnt'];
    }

    mysqli_close($conn);
    return ['success' => true, 'data' => $stats];
}
