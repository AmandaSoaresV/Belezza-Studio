<?php
// Arquivo de exemplo da conexão.
// Copie para conexao.php e preencha com os dados reais do seu ambiente.

$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco";

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $dbname
);

if($conn->connect_error){
    die("Erro na conexão: " . $conn->connect_error);
}

?>
