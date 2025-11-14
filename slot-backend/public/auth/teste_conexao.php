<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Tenta carregar o config de diferentes locais possíveis
$config_paths = [
    __DIR__ . '/config.php',
    __DIR__ . '/../src/config.php',
    __DIR__ . '/../../src/config.php'
];

$config_loaded = false;
foreach ($config_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $config_loaded = true;
        break;
    }
}

if (!$config_loaded) {
    die(json_encode(['error' => 'Config file not found in any location']));
}

// Testa a conexão
try {
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    
    if ($result['test'] == 1) {
        echo json_encode(['status' => 'success', 'message' => 'Conexão com banco OK!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query de teste falhou']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}