<?php
$senha_limpa = '123456'; // Senha que você usará para logar
$hash = password_hash($senha_limpa, PASSWORD_DEFAULT);
echo "Hash gerado: " . $hash;
?>