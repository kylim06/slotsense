<?php
require_once __DIR__ . "/../../src/functions.php";
require_auth();
if (!is_admin()) {
    http_response_code(403);
    exit("Acesso negado.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - SlotSense</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

<!-- NAVBAR -->
<header class="w-full bg-gray-800 text-white py-4 px-6 flex justify-between items-center shadow">
    <h1 class="text-2xl font-semibold">Painel Administrativo</h1>

    <div class="flex gap-4">
        <a href="novo_jogo.php"
           class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white font-medium">
            + Novo Jogo
        </a>

        <a href="../auth/logout.php"
           class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white font-medium">
            Sair
        </a>
    </div>
</header>

<!-- CONTAINER -->
<div class="max-w-6xl mx-auto mt-10 bg-gray-800 shadow-lg rounded-lg p-8">

    <h2 class="text-xl font-bold mb-6">Gerenciar Jogos</h2>

    <!-- TABELA -->
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-700 text-gray-200 uppercase text-sm">
                <th class="p-3">ID</th>
                <th class="p-3">Imagem</th>
                <th class="p-3">Nome</th>
                <th class="p-3 w-32">Porcentagem</th>
                <th class="p-3">Ações</th>
            </tr>
        </thead>
        <tbody id="tabela-jogos" class="bg-gray-900"></tbody>
    </table>

    <button id="salvarBtn"
            class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded">
        Salvar Alterações de Porcentagem
    </button>

</div>

<script>
async function carregarJogos() {
    const tabela = document.getElementById("tabela-jogos");

    tabela.innerHTML = `
        <tr><td colspan="5" class="text-center p-4">Carregando...</td></tr>
    `;

    const req = await fetch("/slot/slot-backend/public/api/jogos_get.php");
    const jogos = await req.json();

    tabela.innerHTML = "";

    if (!Array.isArray(jogos) || jogos.length === 0) {
        tabela.innerHTML = `
            <tr><td colspan="5" class="text-center p-4 text-gray-300">Nenhum jogo cadastrado.</td></tr>
        `;
        return;
    }

    jogos.forEach(jogo => {
        tabela.innerHTML += `
            <tr class="border-b border-gray-700">
                <td class="p-3">${jogo.id}</td>

                <td class="p-3">
                    <img src="${jogo.imagem}" class="w-16 h-16 rounded shadow">
                </td>

                <td class="p-3 font-medium">${jogo.nome}</td>

                <td class="p-3">
                    <input 
                        type="number"
                        min="0" max="100"
                        value="${jogo.porcentagem}"
                        class="porcentInput w-20 px-2 py-1 border rounded bg-gray-700"
                        data-id="${jogo.id}"
                    >
                </td>

                <td class="p-3 flex gap-3">
                    <a href="editar_jogos.php?id=${jogo.id}"
                       class="text-blue-400 hover:text-blue-300 font-semibold">
                        Editar
                    </a>

                    <button onclick="deletar(${jogo.id})"
                            class="text-red-400 hover:text-red-300 font-semibold">
                        Excluir
                    </button>
                </td>
            </tr>
        `;
    });
}

async function salvarAlteracoes() {
    const inputs = document.querySelectorAll(".porcentInput");
    const valores = [];

    inputs.forEach(inp => {
        valores.push({
            id: inp.dataset.id,
            porcentagem: inp.value
        });
    });

    const req = await fetch("/slot/slot-backend/public/api/jogos_update.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ valores })
    });

    const res = await req.json();

    if (res.ok) {
        alert("Porcentagens atualizadas com sucesso!");
    } else {
        alert("Erro ao atualizar.");
    }
}

async function deletar(id) {
    if (!confirm("Tem certeza que deseja excluir este jogo?")) return;

    const req = await fetch(`/slot/slot-backend/public/api/jogos_delete.php?id=${id}`);
    const res = await req.json();

    if (res.ok) {
        alert("Jogo excluído!");
        carregarJogos();
    } else {
        alert("Erro ao excluir jogo!");
    }
}

document.getElementById("salvarBtn").onclick = salvarAlteracoes;

carregarJogos();
</script>

</body>
</html>
