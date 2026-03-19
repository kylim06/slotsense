<?php
session_start();

// Limpa a sessão
$_SESSION = [];
session_destroy();

// Redireciona para a tela de login
header("Location: /slotsense/admin.php");
exit;
