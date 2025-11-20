<?php
require_once __DIR__ . "/../../src/bootstrap.php";
require_auth();
if (!is_admin()) exit(json_encode(['error' => 'Acesso negado']));

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM jogos WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(['ok' => true]);
