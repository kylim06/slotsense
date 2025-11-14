<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__ . "/../../../src/config.php");


// Retorna todos os jogos
$stmt = $pdo->prepare("SELECT id, nome, imagem, porcentagem FROM jogos ORDER BY popularidade DESC, id ASC");
$stmt->execute();
$jogos = $stmt->fetchAll();

echo json_encode($jogos);
