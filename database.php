<?php
// Configuração do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'colegio_tia_lucinda');
define('DB_USER', 'root');
define('DB_PASS', ''); // Coloque sua senha do MySQL aqui

// Criar conexão
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Iniciar sessão
session_start();
?>