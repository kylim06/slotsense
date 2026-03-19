<?php
/**
 * =========================================================================
 * ARQUIVO DE CONFIGURAÇÃO (config.php)
 * =========================================================================
 * Define as credenciais de acesso ao banco de dados MySQL via PDO.
 * É essencial que estas credenciais não sejam exportadas ou tornadas
 * públicas em ambientes de produção.
 */

// Força o PHP a respeitar tipagem estrita localmente
declare(strict_types=1);

// Variáveis de acesso (Hostname, nome do DB, usuário e senha)
$DB_HOST = '127.0.0.1';
$DB_NAME = 'slot_sense';
$DB_USER = 'root';
$DB_PASS = '';

// Opções extras para o driver PDO do Banco de Dados
$options = [
    // Define que falhas serão lançadas em formato Exception (Try/Catch)
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    
    // Retorna resultados na forma de um array associativo ['coluna' => 'valor']
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Tenta formalmente conectar ao Banco de Dados com UTF-8
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // Caso o servidor caia ou as credenciais estejam erradas, bloqueia acesso e mostra erro genérico
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: '. $e->getMessage()]);
    exit;
}
