<?php
session_start();

require_once __DIR__ . '/config.php';

function require_auth() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error'=>'Unauthorized']);
        exit;
    }
}

function is_admin(): bool {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

// Escapar entrada simples
function input_get(string $key, $default = null) {
    return $_GET[$key] ?? $default;
}

function input_post(string $key, $default = null) {
    return $_POST[$key] ?? $default;
}

