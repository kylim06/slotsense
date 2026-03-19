<?php
/**
 * =========================================================================
 * EDIÇÃO DE JOGO NO PAINEL ADMIN
 * =========================================================================
 */
require_once __DIR__ . '/../../src/functions.php';
require_auth();
if (!is_admin()) { echo "Acesso Negado"; exit; }

$id = (int)input_get('id');
$stmt = $pdo->prepare("SELECT * FROM jogos WHERE id = ?");
$stmt->execute([$id]);
$jogo = $stmt->fetch();

if (!$jogo) { die('Jogo não encontrado.'); }

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = input_post('nome');
    $provedora = input_post('provedora');
    $porcentagem = (int)input_post('porcentagem', 0);

    if (empty($nome) || empty($provedora)) {
        $erro = "Nome e Provedora são obrigatórios.";
    } else {
        $imagemNome = $jogo['imagem'];
        
        // Se eviou imagem nova, substitui
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagemNome = preg_replace('/[^a-zA-Z0-9_\-]/', '', strtolower($nome)) . '.' . $ext;
            
            $destinoDir = __DIR__ . '/../../../img/jogos_' . $provedora . '/';
            if (!is_dir($destinoDir)) { mkdir($destinoDir, 0777, true); }
            
            move_uploaded_file($_FILES['imagem']['tmp_name'], $destinoDir . $imagemNome);
        }

        try {
            $update = $pdo->prepare("UPDATE jogos SET nome = ?, provedora = ?, imagem = ?, porcentagem = ? WHERE id = ?");
            $update->execute([$nome, $provedora, $imagemNome, $porcentagem, $id]);
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $erro = "Falha ao gravar no banco: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Editar Jogo - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f2f2f2; padding: 20px; }
        .box { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; }
        input, select, button { width: 100%; margin-top: 8px; margin-bottom: 15px; padding: 10px; box-sizing: border-box; }
        button { background: #ffb400; font-weight: bold; border: none; cursor: pointer; border-radius: 5px;}
        .voltar { text-decoration: none; color: #555; }
        .preview { display: block; width: 100px; margin-bottom: 10px; border-radius: 10px; background: #333;}
    </style>
</head>
<body>
    <div class="box">
        <h2>Editar: <?=htmlspecialchars($jogo['nome'])?></h2>
        <?php if($erro): ?><p style="color:red;"><?=$erro?></p><?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <label>Nome do Jogo</label>
            <input type="text" name="nome" value="<?=htmlspecialchars($jogo['nome'])?>" required>

            <label>Provedora</label>
            <select name="provedora" required>
                <option value="pgsoft" <?=$jogo['provedora']=='pgsoft'?'selected':''?>>PG Soft</option>
                <option value="pragmatic" <?=$jogo['provedora']=='pragmatic'?'selected':''?>>Pragmatic Play</option>
                <option value="tada" <?=$jogo['provedora']=='tada'?'selected':''?>>TaDa Gaming</option>
                <option value="wg" <?=$jogo['provedora']=='wg'?'selected':''?>>WG</option>
            </select>

            <label>Capa Atual</label>
            <?php if($jogo['imagem']): ?>
                <img src="/slotsense/img/jogos_<?=$jogo['provedora']?>/<?=$jogo['imagem']?>" class="preview">
            <?php endif; ?>
            <label>Trocar Capa (Opcional)</label>
            <input type="file" name="imagem" accept="image/*">

            <label>Porcentagem Fixa (0 para aleatório animado do site)</label>
            <input type="number" name="porcentagem" value="<?=$jogo['porcentagem']?>" min="0" max="100">

            <button type="submit">Salvar Alterações</button>
        </form>
        <a href="index.php" class="voltar">← Voltar para a lista</a>
    </div>
</body>
</html>
