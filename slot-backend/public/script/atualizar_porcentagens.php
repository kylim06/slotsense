<?php
require_once(__DIR__ . "/../../../src/config.php");

// regra exemplo: random entre 20-90; jogos populares >+10
$stmt = $pdo->query("SELECT id, popularidade FROM jogos");
$jogos = $stmt->fetchAll();

$upd = $pdo->prepare("UPDATE jogos SET porcentagem = ?, atualizado_em = NOW() WHERE id = ?");
foreach ($jogos as $j) {
    $base = rand(20, 90);
    if ($j['popularidade'] > 50) $base = min(100, $base + 10);
    $upd->execute([$base, $j['id']]);
}
echo "Atualizado\n";