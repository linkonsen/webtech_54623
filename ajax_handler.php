<?php
// ============================================================
//  ajax_handler.php  —  AJAX ENTRY POINT
//
//  All AJAX requests from the View hit this file.
//  It reads the 'action' parameter, routes to the correct
//  controller function, and returns JSON.
//
//  Accepts both GET (fetch/stats) and POST (mutate) requests.
// ============================================================

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Allow AJAX from same origin
header('Access-Control-Allow-Origin: same-origin');

require_once __DIR__ . '/controller/BookController.php';

// ── Collect input ───────────────────────────────────────────
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Support JSON body as well as form-encoded POST
$raw    = file_get_contents('php://input');
$json   = json_decode($raw, true);

// Merge: priority: JSON body > POST > GET
$input  = array_merge($_GET, $_POST, $json ?? []);

$action = strtolower(trim($input['action'] ?? ''));

// ── Route to controller ─────────────────────────────────────
$response = match ($action) {
    'add'    => controllerAddBook($input),
    'list'   => controllerGetBooks($input),
    'get'    => controllerGetBook($input),
    'update' => controllerUpdateBook($input),
    'delete' => controllerDeleteBook($input),
    'stats'  => controllerGetStats(),
    default  => ['success' => false, 'message' => "Unknown action: '{$action}'"]
};

// ── Emit JSON ───────────────────────────────────────────────
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
exit;
