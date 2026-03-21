<?php
/**
 * =========================================================================
 * DASHBOARD ADMIN (index.php)
 * =========================================================================
 */
require_once __DIR__ . '/../../src/functions.php';
require_auth();
if (!is_admin()) { echo "Acesso Negado. Você não é admin."; exit; }

// Verifica se há alguma mensagem
$msg = $_GET['msg'] ?? '';

// Pega lista de jogos incluindo a provedora
$stmt = $pdo->query("SELECT * FROM jogos ORDER BY provedora ASC, id DESC");
$jogos = $stmt->fetchAll();

// Agrupar jogos pela provedora para exibir tabelas separadas
$jogos_por_provedora = [];
foreach ($jogos as $j) {
    $p = strtoupper($j['provedora']);
    if (empty($p)) $p = 'OUTRAS';
    $jogos_por_provedora[$p][] = $j;
}
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
        .provedora-title { color: #ffb400; font-size: 1.8rem; margin-top: 40px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #444; text-transform: uppercase; letter-spacing: 1px;}
        table { width: 100%; border-collapse: collapse; background: #333; border-radius: 8px; overflow: hidden; margin-bottom: 20px;}
        th, td { padding: 12px 15px; text-align: left; }
        th { background: #1a1a1a; color: #ffb400; font-weight: 600; }
        tr:nth-child(even) { background: #2a2a2a; }
        tr:hover { background: #444; }
        .img-preview { border-radius: 8px; object-fit: cover; }
        .btn-editar { background: #ffb400; color: #000; padding: 5px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .btn-deletar { background: #d9534f; color: #fff; padding: 5px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-left: 5px; }
        .msg-sucesso { background: #44d06b; color: #000; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: bold; text-align: center;}
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
        
        <?php if(empty($jogos_por_provedora)): ?>
            <div style="background:#333; padding: 40px; text-align:center; border-radius:8px;">
                Nenhum jogo cadastrado no sistema.
            </div>
        <?php else: ?>
            
            <?php foreach ($jogos_por_provedora as $provedora => $lista): ?>
                
                <h2 class="provedora-title">🎮 <?= htmlspecialchars($provedora) ?></h2>
                
                <table>
                    <thead>
                        <tr>
                            <th>Capa</th>
                            <th>Nome</th>
                            <th>Win% Fixo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista as $j): ?>
                        <tr>
                            <td>
                                <?php if($j['imagem']): ?>
                                    <img src="/slotsense/img/jogos_<?=htmlspecialchars($j['provedora'])?>/<?=htmlspecialchars($j['imagem'])?>" width="60" class="img-preview">
                                <?php else: ?>
                                    <span style="color:#aaa;">Sem capa</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($j['nome']) ?></td>
                            <td><?= $j['porcentagem'] > 0 ? $j['porcentagem'].'%' : '<span style="color:#aaa;">Dinâmico/Site</span>' ?></td>
                            <td>
                                <a href="editar_jogo.php?id=<?= $j['id'] ?>" class="btn-editar">Editar</a>
                                <a href="deletar_jogo.php?id=<?= $j['id'] ?>" class="btn-deletar" onclick="return confirm('Tem certeza que deseja excluir o jogo \'<?= htmlspecialchars($j['nome'], ENT_QUOTES) ?>\'? Essa ação afetará a página inicial e não pode ser desfeita!');">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            <?php endforeach; ?>
            
        <?php endif; ?>
    </div>
</body>
</html>
