<?php
/**
 * =========================================================================
 * ARQUIVO DE FUNÇÕES GLOBAIS (functions.php)
 * =========================================================================
 * Define funções úteis para uso por toda a aplicação (backend), lidando
 * principalmente com segurança de input, autenticação em sessão, entre outros.
 */

// Inicia a sessão global para permitir variáveis $_SESSION por todo o sistema
session_start();

// Exige o arquivo de configuração e conexão ao PDO
require_once __DIR__ . '/config.php';

/**
 * Função responsável por checar se o usuário de fato efetuou login.
 * Retorna código 401 se não autenticado.
 */
function require_auth() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);

        echo json_encode(['error'=>'Unauthorized - Você precisa se autenticar']);
        exit;
    }
}
/**
 * Retorna indicativo de se a sessão atual em vigor tem o status de `is_admin`
 * @return bool
 */
function is_admin(): bool {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

/**
 * Função utilitária para capturar dados via URL (GET).
 *
 * @param string $key Chave desejada na URL
 * @param mixed $default Valor de fallback caso a chave não exista
 * @return mixed O valor resgatado ou null.
 */
function input_get(string $key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Função utilitária para capturar dados de um envio de Formulário ou API pura (POST)
 *
 * @param string $key Chave desejada no envio
 * @param mixed $default Valor de fallback
 * @return mixed
 */
function input_post(string $key, $default = null) {
    return $_POST[$key] ?? $default;
}
