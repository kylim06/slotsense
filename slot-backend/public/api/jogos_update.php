<?php

require_once __DIR__ . "/../../../src/bootstrap.php";

// Checar sessão e admin
require_auth();

if (!is_admin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Recebe JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['valores'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

$pdo->beginTransaction();

try {

    $stmt = $pdo->prepare("
        UPDATE jogos 
        SET porcentagem = ?, atualizado_em = NOW()
        WHERE id = ?
    ");

    foreach ($input['valores'] as $item) {
        $id = (int) ($item['id'] ?? 0);
        $p  = (int) ($item['porcentagem'] ?? 0);

        if ($id <= 0 || $p < 0 || $p > 100) {
            continue;
        }

        $stmt->execute([$p, $id]);
    }

    $pdo->commit();
    echo json_encode(['ok' => true]);

} catch (Exception $e) {

    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'update failed']);

}
