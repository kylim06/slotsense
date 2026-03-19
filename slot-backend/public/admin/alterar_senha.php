<?php
/**
 * =========================================================================
 * PAINEL ADMIN: ALTERAÇÃO DE CREDENCIAIS (E-MAIL E SENHA)
 * =========================================================================
 */
require_once __DIR__ . '/../../src/functions.php';
require_auth();
if (!is_admin()) { echo "Acesso Negado."; exit; }

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_email = trim(input_post('novo_email'));
    $nova_senha = input_post('nova_senha');
    
    if (empty($novo_email) || empty($nova_senha)) {
        $erro = "E-mail e Senha são obrigatórios!";
    } elseif (strlen($nova_senha) < 6) {
        $erro = "Por motivos de segurança, a senha deve ter pelo menos 6 caracteres.";
    } else {
        // Criptografia irreversível usando padrão BCRYPT seguro atual
        $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET email = ?, senha_hash = ? WHERE id = ?");
            $stmt->execute([$novo_email, $hash, $_SESSION['user_id']]);
            $sucesso = "Credenciais alteradas com sucesso! Anote na sua agenda para não esquecer.";
        } catch (Exception $e) {
            $erro = "Erro ao atualizar. Este e-mail pode já estar em uso por outro administrador.";
        }
    }
}

// Resgata o email atual para mostrar no formulário
$stmt = $pdo->prepare("SELECT email FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Segurança - Slot Sense</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #232323; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: #333; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); width: 100%; max-width: 400px; text-align: center;}
        h2 { color: #5bc0de; margin-bottom: 25px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #555; background: #222; color: white; box-sizing: border-box;}
        button { width: 100%; background: #5bc0de; color: #000; padding: 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 1.1rem;}
        button:hover { background: #31b0d5; }
        .msg-erro { color: #ff6b6b; font-weight: bold; margin-bottom: 15px; }
        .msg-sucesso { color: #44d06b; font-weight: bold; margin-bottom: 15px; }
        .voltar { display: block; margin-top: 20px; color: #aaa; text-decoration: none; }
        .voltar:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="box">
        <h2>🔒 Alterar Credenciais</h2>
        
        <?php if($erro): ?><div class="msg-erro"><?=$erro?></div><?php endif; ?>
        <?php if($sucesso): ?><div class="msg-sucesso"><?=$sucesso?></div><?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Novo E-mail de Login:</label>
                <input type="email" name="novo_email" value="<?=htmlspecialchars($user['email'])?>" required>
            </div>
            
            <div class="form-group">
                <label>Nova Senha Segura:</label>
                <input type="password" name="nova_senha" placeholder="Digite pelo menos 6 letras/números" required>
            </div>
            
            <button type="submit">Salvar Nova Senha</button>
        </form>
        
        <a href="index.php" class="voltar">← Voltar ao Painel</a>
    </div>
</body>
</html>
