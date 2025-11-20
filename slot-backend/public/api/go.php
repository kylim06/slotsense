<?php
require_once __DIR__ . "/../../src/bootstrap.php";

// ID obrigatório
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    http_response_code(400);
    exit("ID inválido");
}

// Busca o jogo
$stmt = $pdo->prepare("SELECT link_affiliate FROM jogos WHERE id = ?");
$stmt->execute([$id]);
$jogo = $stmt->fetch();

if (!$jogo) {
    http_response_code(404);
    exit("Jogo não encontrado");
}

// Registra clique no banco (para ajustar porcentagens depois)
$pdo->prepare("UPDATE jogos SET clicks = clicks + 1 WHERE id = ?")
    ->execute([$id]);

// Mapa de rastreamento (opcional, mas útil)
$track = $pdo->prepare("
    INSERT INTO cliques (jogo_id, ip, user_agent, referrer)
    VALUES (?, ?, ?, ?)
");

$track->execute([
    $id,
    $_SERVER['REMOTE_ADDR'] ?? null,
    $_SERVER['HTTP_USER_AGENT'] ?? '',
    $_SERVER['HTTP_REFERER'] ?? ''
]);

// Redireciona para o link de afiliado
header("Location: " . $jogo['link_affiliate']);
exit;
