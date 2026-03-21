<?php
/**
 * =========================================================================
 * API PARA RETORNAR JOGOS (jogos_get.php)
 * =========================================================================
 * Este end-point fornece a base de dados de todos os jogos existentes,
 * lendo com o driver PDO e os retornando no formato JSON (utilizados se
 * a aplicação optar por usar esse driver de dados ou de administração externa).
 */

header('Content-Type: application/json; charset=utf-8');

// Adquire config e PDO
require_once(__DIR__ . "/../../src/config.php");

try {
    // Retorna todos os jogos, garantindo ordem de popularidade ou ID
    $stmt = $pdo->prepare("SELECT id, nome, provedora, imagem, porcentagem FROM jogos ORDER BY id ASC");
    $stmt->execute();
    
    // Obtém lista como Array do lado PHP e converte para JSON Puro para os clientes
    $jogos = $stmt->fetchAll();
    
    // Impressão da saída do formato puro
    echo json_encode($jogos);
} catch (Exception $e) {
    // Blindagem de erro (Security failback alert)
    http_response_code(500);
    echo json_encode(['error' => 'Falha ao recuperar a listagem via banco de dados.']);
}
