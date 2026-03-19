<?php
/**
 * =========================================================================
 * DASHBOARD ADMIN (index.php)
 * =========================================================================
 */
require_once __DIR__ . '/../../src/functions.php';
require_auth();
if (!is_admin()) { echo "Acesso Negado. Você não é admin."; exit; }

// Pega lista de jogos incluindo a provedora
$stmt = $pdo->query("SELECT * FROM jogos ORDER BY provedora ASC, id DESC");
$jogos = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Painel Admin - Slot Sense</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #232323; color: white; margin: 0; padding: 0;}
        .header { background: #111; padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #ffb400;}
        .header h1 { margin: 0; font-size: 1.5rem; color:#ffb400;}
        .header a { color: #fff; text-decoration: none; padding: 8px 15px; background: #d9534f; border-radius: 4px; font-weight:bold;}
        .container { padding: 30px; max-width: 1000px; margin: auto; }
        .btn-novo { display: inline-block; background: #44d06b; color: #111; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-bottom: 20px;}
        table { width: 100%; border-collapse: collapse; background: #333; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: left; }
        th { background: #1a1a1a; color: #ffb400; font-weight: 600; }
        tr:nth-child(even) { background: #2a2a2a; }
        tr:hover { background: #444; }
        .img-preview { border-radius: 8px; object-fit: cover; }
        .btn-editar { background: #ffb400; color: #000; padding: 5px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Painel Admin</h1>
        <div>
            <span>Olá, <?=htmlspecialchars($_SESSION['user_name'])?> &nbsp;</span>
            <a href="alterar_senha.php" style="background:#5bc0de; margin-right:5px;">Alterar Acesso</a>
            <a href="../auth/logout.php">Sair</a>
        </div>
    </div>
    <div class="container">
        <a href="novo_jogo.php" class="btn-novo">+ Cadastrar Novo Jogo</a>
        
        <table>
            <thead>
                <tr>
                    <th>Capa</th>
                    <th>Nome</th>
                    <th>Provedora</th>
                    <th>Win% Fixo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($jogos as $j): ?>
                <tr>
                    <td>
                        <?php if($j['imagem']): ?>
                            <img src="/slotsense/img/jogos_<?=htmlspecialchars($j['provedora'])?>/<?=htmlspecialchars($j['imagem'])?>" width="60" class="img-preview">
                        <?php else: ?>
                            <span style="color:#aaa;">Sem capa</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($j['nome']) ?></td>
                    <td><strong style="text-transform:uppercase; color:#ffb400;"><?= htmlspecialchars($j['provedora']) ?></strong></td>
                    <td><?= $j['porcentagem'] > 0 ? $j['porcentagem'].'%' : '<span style="color:#aaa;">Dinâmico/Site</span>' ?></td>
                    <td>
                        <a href="editar_jogo.php?id=<?= $j['id'] ?>" class="btn-editar">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($jogos)): ?>
                    <tr><td colspan="5" style="text-align:center;">Nenhum jogo cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
