<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/src/config.php";

// ALTERE AQUI:
$email = "admin@teste.com";   // email do administrador
$novaSenha = "admin123";      // sua nova senha

$hash = password_hash($novaSenha, PASSWORD_DEFAULT);

echo "<h2>Resetando senha...</h2>";

$stmt = $pdo->prepare("UPDATE usuarios SET senha_hash = ? WHERE email = ?");
$stmt->execute([$hash, $email]);

if ($stmt->rowCount() > 0) {
    echo "<p>Senha resetada com sucesso!</p>";
    echo "<p>Nova senha: <b>$novaSenha</b></p>";
} else {
    echo "<p>Usuário não encontrado!</p>";
}
