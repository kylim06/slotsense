<?php
/**
 * =========================================================================
 * DELETAR JOGO
 * =========================================================================
 * Recebe o ID do jogo via GET, deleta do DB e volta pro painel.
 */
require_once __DIR__ . '/../../src/functions.php';
require_auth();
if (!is_admin()) { echo "Acesso Negado."; exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Busca a imagem para tentar apagar fisiscamente (Opcional, mas limpa o servidor)
    $stmt = $pdo->prepare("SELECT imagem, provedora FROM jogos WHERE id = ?");
    $stmt->execute([$id]);
    $jogo = $stmt->fetch();

    if ($jogo) {
        $arquivo = __DIR__ . "/../../../img/jogos_" . $jogo['provedora'] . "/" . $jogo['imagem'];
        if ($jogo['imagem'] && file_exists($arquivo)) {
            @unlink($arquivo);
        }

        // Deleta os cliques atrelados (se houver CASCADE no BD não precisaria, mas é bom garantir)
        $pdo->prepare("DELETE FROM cliques WHERE jogo_id = ?")->execute([$id]);

        // Deleta o jogo
        $pdo->prepare("DELETE FROM jogos WHERE id = ?")->execute([$id]);
    }
}

// Retorna ao painel
header("Location: index.php?msg=deleted");
exit;
