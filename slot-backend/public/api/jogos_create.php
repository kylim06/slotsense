<?php

// carrega funções E sessão
require_once dirname(__DIR__, 2) . "/src/functions.php";

require_auth();
if (!is_admin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado']);
    exit;
}

header("Content-Type: application/json");

// pega JSON enviado pelo fetch
$dados = json_decode(file_get_contents("php://input"), true);

if (!$dados) {
    echo json_encode(['error' => 'JSON inválido']);
    exit;
}

$nome          = trim($dados['nome'] ?? '');
$imagem        = trim($dados['imagem'] ?? '');
$link_aff      = trim($dados['link_affiliate'] ?? '');
$porcentagem   = (int)($dados['porcentagem'] ?? 0);
$popularidade  = (int)($dados['popularidade'] ?? 0);

if ($nome === '' || $imagem === '' || $link_aff === '') {
    echo json_encode(['error' => 'Campos obrigatórios faltando']);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO jogos (nome, imagem, link_affiliate, porcentagem, popularidade)
    VALUES (?, ?, ?, ?, ?)
");

try {
    $stmt->execute([$nome, $imagem, $link_aff, $porcentagem, $popularidade]);
    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Erro ao salvar: ' . $e->getMessage()]);
}
