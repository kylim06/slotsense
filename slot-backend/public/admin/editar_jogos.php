<?php
require_once __DIR__ . "/../../src/functions.php";
require_auth();
if (!is_admin()) exit("Acesso negado");

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) exit("ID inválido");

require_once __DIR__ . "/../../src/bootstrap.php";

$stmt = $pdo->prepare("SELECT * FROM jogos WHERE id = ?");
$stmt->execute([$id]);
$jogo = $stmt->fetch();

if (!$jogo) exit("Jogo não encontrado");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Jogo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

<div class="max-w-xl mx-auto mt-16 bg-gray-800 p-8 rounded-lg shadow-xl">
    <h2 class="text-2xl font-bold mb-6">Editar Jogo</h2>

    <form id="formEdit">

        <input type="hidden" name="id" value="<?= $jogo['id'] ?>">

        <label>Nome</label>
        <input name="nome" class="w-full p-2 bg-gray-700 mb-4" value="<?= $jogo['nome'] ?>">

        <label>Imagem (URL)</label>
        <input name="imagem" class="w-full p-2 bg-gray-700 mb-4" value="<?= $jogo['imagem'] ?>">

        <label>Link Afiliado</label>
        <input name="link_affiliate" class="w-full p-2 bg-gray-700 mb-4" value="<?= $jogo['link_affiliate'] ?>">

        <label>Porcentagem</label>
        <input type="number" name="porcentagem" class="w-full p-2 bg-gray-700 mb-4"
               min="0" max="100" value="<?= $jogo['porcentagem'] ?>">

        <label>Popularidade</label>
        <input type="number" name="popularidade" class="w-full p-2 bg-gray-700 mb-4"
               min="0" value="<?= $jogo['popularidade'] ?>">

        <button class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded">Salvar alterações</button>
    </form>

    <p id="msg" class="mt-4 text-center"></p>
</div>

<script>
document.querySelector("#formEdit").addEventListener("submit", async (e) => {
    e.preventDefault();

    const dados = Object.fromEntries(new FormData(e.target).entries());

    const req = await fetch("/slot/slot-backend/public/api/jogos_edit.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(dados)
    });

    const res = await req.json();
    const msg = document.getElementById("msg");

    msg.classList.remove("text-red-400", "text-green-400");

    if (res.ok) {
        msg.textContent = "Alterações salvas!";
        msg.classList.add("text-green-400");
    } else {
        msg.textContent = "Erro: " + res.error;
        msg.classList.add("text-red-400");
    }
});
</script>

</body>
</html>
