<?php

require_once __DIR__ . "/../../src/bootstrap.php";

// Busca todos os jogos
$stmt = $pdo->prepare("
    SELECT id, nome, imagem, porcentagem
    FROM jogos
    ORDER BY popularidade DESC, id ASC
");
$stmt->execute();

echo json_encode($stmt->fetchAll());
