<?php
require_once __DIR__ . '/../src/functions.php';
require_auth();
if (!is_admin()) { echo "Forbidden"; exit; }

// pega lista de jogos
$stmt = $pdo->query("SELECT * FROM jogos ORDER BY id");
$jogos = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Painel Admin</title></head>
<body>
<h1>Olá, <?=htmlspecialchars($_SESSION['user_name'])?></h1>
<a href="/public/auth/logout.php">Sair</a>
<h2>Jogos</h2>
<table border="1">
  <tr><th>ID</th><th>Nome</th><th>Imagem</th><th>Porcentagem</th><th>Ações</th></tr>
  <?php foreach($jogos as $j): ?>
  <tr>
    <td><?= $j['id'] ?></td>
    <td><?= htmlspecialchars($j['nome']) ?></td>
    <td><?php if($j['imagem']): ?><img src="/public/uploads/<?=htmlspecialchars($j['imagem'])?>" width="60"><?php endif; ?></td>
    <td><?= $j['porcentagem'] ?>%</td>
    <td>
      <a href="editar_jogo.php?id=<?= $j['id'] ?>">Editar</a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>

<p><a href="novo_jogo.php">Criar novo jogo</a></p>
</body>
</html>