<?php
$servername = "localhost"; // Nome do servidor MySQL
$username = "root"; // Nome de usuário do MySQL
$password = ""; // Senha do MySQL
$dbname = "test_db"; // Nome do banco de dados MySQL

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão foi estabelecida corretamente
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
