<?php
session_start();
session_destroy();

// Redireciona para a tela de login
header("Location: /slotsense/admin.php");
exit;
