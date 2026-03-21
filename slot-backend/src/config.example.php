<?php
/**
 * =========================================================================
 * ARQUIVO DE CONFIGURAÇÃO DE EXEMPLO (config.example.php)
 * =========================================================================
 * Copie este arquivo e renomeie para "config.php" na sua hospedagem oficial
 * e substitua os dados de conexão do seu Banco de Dados MySQL na web.
 */
declare(strict_types=1);

$DB_HOST = 'localhost'; // ou '127.0.0.1'
$DB_NAME = 'nome_do_seu_banco';
$DB_USER = 'usuario_do_banco';
$DB_PASS = 'senha_segura';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed no servidor de produção.']);
    exit;
}
