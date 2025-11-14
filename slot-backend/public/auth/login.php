<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// VERIFICA MÉTODO PRIMEIRO
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// AGORA INICIA A SESSÃO
session_start();

// CONFIG
require_once(__DIR__ . '/../../src/config.php');

// DADOS DO FORM
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// VALIDAÇÃO
if (empty($email) || empty($senha)) {
    http_response_code(400);
    echo json_encode(['error' => 'missing']);
    exit;
}

// BUSCA USUÁRIO
$stmt = $pdo->prepare("SELECT id, senha_hash, nome, is_admin FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// VERIFICA SENHA
if (!$user || !password_verify($senha, $user['senha_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'invalid credentials']);
    exit;
}

// LOGIN BEM-SUCEDIDO
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['nome'];
$_SESSION['is_admin'] = (bool)$user['is_admin'];

echo json_encode(['ok' => true, 'name' => $user['nome']]);