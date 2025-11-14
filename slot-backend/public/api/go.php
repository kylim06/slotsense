<?php
require_once(__DIR__ . "/../../../src/config.php");

// Ex.: go.php?id=123
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(400); echo "Bad id"; exit; }

// Busca link e registra clique
$stmt = $pdo->prepare("SELECT link_affiliate FROM jogos WHERE id = ?");
$stmt->execute([$id]);
$j = $stmt->fetch();

if (!$j) { http_response_code(404); echo "Not found"; exit; }

// registra clique
$ins = $pdo->prepare("INSERT INTO cliques (jogo_id, ip, user_agent, referrer) VALUES (?, ?, ?, ?)");
$ins->execute([$id, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null, $_SERVER['HTTP_REFERER'] ?? null]);

// redireciona
header("Location: " . $j['link_affiliate']);
exit;