<?php
// ============================================================
//  controller/BookController.php  —  CONTROLLER LAYER
//
//  Validates and sanitises input, calls Model functions,
//  and prepares the JSON response.
//  Does NOT touch the database directly.
// ============================================================

require_once __DIR__ . '/../model/BookModel.php';

// Allowed values for the status field
const VALID_STATUSES = ['available', 'borrowed', 'reserved'];

// Allowed categories
const VALID_CATEGORIES = [
    'Programming', 'Computer Science', 'Fiction', 'History',
    'Science', 'Mathematics', 'Psychology', 'Philosophy',
    'Engineering', 'Business', 'Other'
];


// ─────────────────────────────────────────
//  Sanitise a plain text string
// ─────────────────────────────────────────
function sanitizeString(string $value): string
{
    return trim(htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8'));
}


// ─────────────────────────────────────────
//  CONTROLLER: Add a new book
// ─────────────────────────────────────────
function controllerAddBook(array $data): array
{
    $title    = sanitizeString($data['title']    ?? '');
    $author   = sanitizeString($data['author']   ?? '');
    $category = sanitizeString($data['category'] ?? '');
    $status   = sanitizeString($data['status']   ?? 'available');

    // Validation
    $errors = [];
    if (strlen($title)  < 2)  $errors[] = 'Title must be at least 2 characters.';
    if (strlen($title)  > 255) $errors[] = 'Title must be under 255 characters.';
    if (strlen($author) < 2)  $errors[] = 'Author must be at least 2 characters.';
    if (!in_array($category, VALID_CATEGORIES)) $errors[] = 'Invalid category selected.';
    if (!in_array($status,   VALID_STATUSES))   $errors[] = 'Invalid status value.';

    if (!empty($errors)) {
        return ['success' => false, 'message' => implode(' ', $errors)];
    }

    // Delegate to Model
    return insertBook($title, $author, $category, $status);
}


// ─────────────────────────────────────────
//  CONTROLLER: Fetch all books
// ─────────────────────────────────────────
function controllerGetBooks(array $data): array
{
    $search = sanitizeString($data['search'] ?? '');
    return getAllBooks($search);
}


// ─────────────────────────────────────────
//  CONTROLLER: Fetch one book (for edit form)
// ─────────────────────────────────────────
function controllerGetBook(array $data): array
{
    $id = (int)($data['id'] ?? 0);
    if ($id < 1) {
        return ['success' => false, 'data' => null, 'message' => 'Invalid book ID.'];
    }
    return getBookById($id);
}


// ─────────────────────────────────────────
//  CONTROLLER: Update a book
// ─────────────────────────────────────────
function controllerUpdateBook(array $data): array
{
    $id       = (int)($data['id'] ?? 0);
    $title    = sanitizeString($data['title']    ?? '');
    $author   = sanitizeString($data['author']   ?? '');
    $category = sanitizeString($data['category'] ?? '');
    $status   = sanitizeString($data['status']   ?? '');

    $errors = [];
    if ($id < 1)              $errors[] = 'Invalid book ID.';
    if (strlen($title)  < 2) $errors[] = 'Title must be at least 2 characters.';
    if (strlen($author) < 2) $errors[] = 'Author must be at least 2 characters.';
    if (!in_array($category, VALID_CATEGORIES)) $errors[] = 'Invalid category.';
    if (!in_array($status,   VALID_STATUSES))   $errors[] = 'Invalid status.';

    if (!empty($errors)) {
        return ['success' => false, 'message' => implode(' ', $errors)];
    }

    return updateBook($id, $title, $author, $category, $status);
}


// ─────────────────────────────────────────
//  CONTROLLER: Delete a book
// ─────────────────────────────────────────
function controllerDeleteBook(array $data): array
{
    $id = (int)($data['id'] ?? 0);
    if ($id < 1) {
        return ['success' => false, 'message' => 'Invalid book ID.'];
    }
    return deleteBook($id);
}


// ─────────────────────────────────────────
//  CONTROLLER: Dashboard stats
// ─────────────────────────────────────────
function controllerGetStats(): array
{
    return getBookStats();
}
