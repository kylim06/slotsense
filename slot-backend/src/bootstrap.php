<?php

// ===========================================
// 1. AUTOLOAD DO COMPOSER
// ===========================================
require_once __DIR__ . '/../vendor/autoload.php';


// ===========================================
// 2. CARREGAR .env
// ===========================================
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


// ===========================================
// 3. CONFIGURAR EXIBIÇÃO DE ERROS
// ===========================================
$appEnv = $_ENV['APP_ENV'] ?? 'prod';

if ($appEnv === 'dev') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}


// ===========================================
// 4. HEADER PADRÃO PARA API
// ===========================================
header('Content-Type: application/json');


// ===========================================
// 5. SESSÃO
// ===========================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ===========================================
// 6. CONEXÃO COM BANCO
// ===========================================
require_once __DIR__ . '/config.php';
