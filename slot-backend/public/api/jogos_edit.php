<?php
require_once __DIR__ . "/../../src/bootstrap.php";
require_auth();
if (!is_admin()) { exit(json_encode(['error' => 'Acesso negado'])); }

$input = json_decode(file_get_contents("php://input"), true);
$id = intval($input['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

$nome = trim($input['nome'] ?? '');
$imagem = trim($input['imagem'] ?? '');
$link = trim($input['link_affiliate'] ?? '');
$porcentagem = intval($input['porcentagem'] ?? 0);
$popularidade = intval($input['popularidade'] ?? 0);

$stmt = $pdo->prepare("
    UPDATE jogos 
    SET nome = ?, imagem = ?, link_affiliate = ?, porcentagem = ?, popularidade = ?
    WHERE id = ?
");

$stmt->execute([$nome, $imagem, $link, $porcentagem, $popularidade, $id]);

echo json_encode(['ok' => true]);
