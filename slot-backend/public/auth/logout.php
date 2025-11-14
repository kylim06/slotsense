<?php
session_start();

// Limpa a sessão
$_SESSION = [];
session_destroy();

// Redireciona para a tela de login
header("Location: /slot/slot-backend/public/auth/login.php");
exit;
