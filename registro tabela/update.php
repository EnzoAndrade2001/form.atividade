<?php
// Inclui o arquivo de configuração da conexão com o banco de dados
require_once('config.php');

// Inicializa a variável para armazenar o ID do usuário a ser editado
$id = null;

// Verifica se foi passado um ID válido via parâmetro GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Verifica se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Coleta os novos dados do formulário
        $novoNome = $_POST['novo_nome'];
        $novoEmail = $_POST['novo_email'];
        
        // Atualiza os dados na tabela users
        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        // Verifica se a preparação da consulta foi bem sucedida
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $conn->error);
        }
        
        // Bind dos parâmetros
        $stmt->bind_param("ssi", $novoNome, $novoEmail, $id);
        
        // Executa a consulta
        if ($stmt->execute()) {
            echo "Dados atualizados com sucesso.";
        } else {
            echo "Erro ao atualizar dados: " . $stmt->error;
        }
        
        // Fecha o statement
        $stmt->close();
    }
    
    // Consulta para obter os dados atuais do usuário
    $sql = "SELECT id, name, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Verifica se a preparação da consulta foi bem sucedida
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }
    
    // Bind do parâmetro
    $stmt->bind_param("i", $id);
    
    // Executa a consulta
    if ($stmt->execute()) {
        // Associa o resultado da consulta a variáveis
        $stmt->bind_result($id, $nome, $email);
        
        // Obtém o resultado da consulta
        $stmt->fetch();
        
        // Fecha o statement
        $stmt->close();
    } else {
        echo "Erro ao executar consulta: " . $stmt->error;
    }
}

// Consulta para listar todos os usuários
$sql_listar = "SELECT id, name, email FROM users";
$result_listar = $conn->query($sql_listar);

// Verifica se a consulta de listagem foi bem sucedida
if ($result_listar === false) {
    die('Erro ao listar usuários: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Usuário</title>
</head>
<body>
    <h2>Atualizar Usuário</h2>
    
    <!-- Lista de usuários -->
    <ul>
        <?php while ($row = $result_listar->fetch_assoc()): ?>
            <li><a href="?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></a></li>
        <?php endwhile; ?>
    </ul>

    <?php if ($id !== null): ?>
        <!-- Formulário para editar o usuário selecionado -->
        <form action="update.php?id=<?php echo $id; ?>" method="POST">
            <label for="novo_nome">Novo Nome:</label><br>
            <input type="text" id="novo_nome" name="novo_nome" value="<?php echo htmlspecialchars($nome); ?>" required><br><br>
            
            <label for="novo_email">Novo Email:</label><br>
            <input type="email" id="novo_email" name="novo_email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>
            
            <button type="submit">Atualizar Dados</button>
        </form>
    <?php endif; ?>

</body>
</html>

<?php
// Fecha a conexão com o banco de dados
$conn->close();
?>
