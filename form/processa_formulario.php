<?php
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $mensagem = $_POST['mensagem'];

    // Configurações da conexão com o banco de dados
    $servername = "localhost"; // Nome do servidor MySQL (geralmente 'localhost')
    $username = "root"; // Nome de usuário do MySQL
    $password = ""; // Senha do MySQL
    $dbname = "test_db"; // Nome do banco de dados MySQL

    // Cria a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se a conexão foi estabelecida corretamente
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Prepara a consulta SQL para inserir os dados
    $sql = "INSERT INTO contacts (nome, email, mensagem) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação da consulta foi bem sucedida
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    // Bind dos parâmetros
    $stmt->bind_param("sss", $nome, $email, $mensagem);

    // Executa a consulta
    if ($stmt->execute()) {
        echo "Dados inseridos com sucesso.";
    } else {
        echo "Erro ao inserir dados: " . $stmt->error;
    }

    // Fecha a conexão e o statement
    $stmt->close();
    $conn->close();
}
?>
