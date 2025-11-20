<?php
// Ativa erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Carrega .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Conexão
$pdo = new PDO(
    "mysql:host=". $_ENV['DB_HOST'] .";dbname=". $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
