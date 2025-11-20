<?php
require_once __DIR__ . "/../../src/bootstrap.php";

// Busca todos os jogos
$stmt = $pdo->query("SELECT id, popularidade, clicks FROM jogos");
$jogos = $stmt->fetchAll();

$update = $pdo->prepare("
    UPDATE jogos 
    SET porcentagem = ?, atualizado_em = NOW()
    WHERE id = ?
");

foreach ($jogos as $j) {

    // Base aleatória (10 a 50)
    $rand = rand(10, 50);

    // Bônus por popularidade (0 a 50)
    $bonus = intval($j["popularidade"] / 2);

    // Penalidade por cliques (limita para não exagerar)
    $pen = isset($j["clicks"]) ? min(20, intval($j["clicks"] / 10)) : 0;

    // Fórmula final
    $percent = $rand + $bonus - $pen;

    // Garantimos que fica entre 20% e 100%
    $percent = max(20, min(100, intval($percent)));

    // Atualiza
    $update->execute([$percent, $j["id"]]);
}

echo json_encode(["ok" => true]);
