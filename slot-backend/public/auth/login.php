<?php
require_once __DIR__ . "/../../src/functions.php";

// Somente método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método não permitido"]);
    exit;
}

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(["error" => "Campos vazios"]);
    exit;
}

// Busca usuário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($senha, $user['senha_hash'])) {
    echo json_encode(["error" => "Credenciais inválidas"]);
    exit;
}

// Login OK
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['nome'];
$_SESSION['is_admin'] = (bool)$user['is_admin'];

// 🔥 Se o login veio do formulário HTML → REDIRECIONA
if (isset($_POST['from_form'])) {
    header("Location: /slot/slot-backend/public/admin/index.php");
    exit;
}

// 🔥 Se o login veio de AJAX → JSON
echo json_encode(["success" => true]);
exit;
