<?php
session_start();
require_once(__DIR__ . "/../../src/functions.php");

require_auth();
if (!is_admin()) {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - SlotSense</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- NAVBAR -->
    <header class="w-full bg-gray-900 text-white py-4 px-6 flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Painel Administrativo</h1>

        <a href="../auth/logout.php" 
           class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white font-medium">
            Sair
        </a>
    </header>

    <!-- CONTAINER -->
    <div class="max-w-5xl mx-auto mt-10 bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-xl font-bold mb-6">Gerenciar Porcentagens dos Jogos</h2>

        <!-- TABELA -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                    <th class="p-3">ID</th>
                    <th class="p-3">Imagem</th>
                    <th class="p-3">Nome</th>
                    <th class="p-3 w-32">Porcentagem</th>
                </tr>
            </thead>
            <tbody id="tabela-jogos"></tbody>
        </table>

        <button id="salvarBtn"
            class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded">
            Salvar Alterações
        </button>

    </div>

    <script>
        async function carregarJogos() {
            const res = await fetch("../api/jogos_get.php");
            const dados = await res.json();

            const tbody = document.getElementById("tabela-jogos");
            tbody.innerHTML = "";

            dados.forEach(jogo => {
                tbody.innerHTML += `
                    <tr class="border-b">
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
                                class="porcentInput w-20 px-2 py-1 border rounded"
                                data-id="${jogo.id}"
                            >
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

            const res = await fetch("../api/jogos_update.php", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({ valores })
            });

            const dados = await res.json();

            if (dados.ok) {
                alert("Porcentagens atualizadas!");
            } else {
                alert("Erro ao atualizar.");
            }
        }

        document.getElementById("salvarBtn").onclick = salvarAlteracoes;

        carregarJogos();
    </script>

</body>
</html>
