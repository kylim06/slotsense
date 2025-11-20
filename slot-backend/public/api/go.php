<?php

require_once __DIR__ . "/../../../src/bootstrap.php";

// Ex.: go.php?id=123
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo "Bad id";
    exit;
}

// Busca o link do jogo
$stmt = $pdo->prepare("SELECT link_affiliate FROM jogos WHERE id = ?");
$stmt->execute([$id]);
$jogo = $stmt->fetch();

if (!$jogo) {
    http_response_code(404);
    echo "Not found";
    exit;
}

// Registrar clique
$insert = $pdo->prepare("
    INSERT INTO cliques (jogo_id, ip, user_agent, referrer) 
    VALUES (?, ?, ?, ?)
");

$insert->execute([
    $id,
    $_SERVER['REMOTE_ADDR']     ?? null,
    $_SERVER['HTTP_USER_AGENT'] ?? null,
    $_SERVER['HTTP_REFERER']    ?? null
]);

// Redirecionar para o link
header("Location: " . $jogo['link_affiliate']);
exit;
