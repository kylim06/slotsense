<?php
session_start();
header("Content-Type: application/json");

require_once(__DIR__ . "/../../src/config.php");

// RECEBE DADOS
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (!$email || !$senha) {
    echo json_encode(['error' => 'Campos vazios']);
    exit;
}

// BUSCA USUÁRIO
$stmt = $pdo->prepare("SELECT id, nome, senha_hash, is_admin FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($senha, $user['senha_hash'])) {
    echo json_encode(['error' => 'Credenciais inválidas']);
    exit;
}

// 🔥 SALVA SESSÃO CORRETAMENTE (O QUE ESTAVA FALTANDO)
$_SESSION['user_id']  = $user['id'];
$_SESSION['user_name'] = $user['nome'];
$_SESSION['is_admin']  = (bool) $user['is_admin'];

// RETORNO
echo json_encode(['success' => true]);
exit;
