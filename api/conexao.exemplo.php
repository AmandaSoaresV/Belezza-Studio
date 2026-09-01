<?php
// Arquivo de exemplo da conexão.
// Copie para conexao.php e preencha com os dados reais do seu ambiente.

$host = "localhost";
$db   = "seu_banco";
$user = "seu_usuario";
$pass = "sua_senha";
$port = 3307;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
