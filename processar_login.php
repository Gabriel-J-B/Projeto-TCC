<?php
session_start();
require 'conexao.php'; // Arquivo de conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta SQL para verificar se as credenciais estão corretas
    $sql = "SELECT id, senha FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (password_verify($senha, $result['senha'])) {
            // Credenciais corretas, atualiza o status para online (opcional)
            $userId = $result['id'];
            $status = 'online';

            $updateSql = "UPDATE usuarios SET status = :status WHERE id = :id";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(":status", $status);
            $updateStmt->bindParam(":id", $userId);
            $updateStmt->execute();

            // Define o ID do usuário na sessão
            $_SESSION['user_id'] = $userId;

            // Redireciona para a página home.php
            header("Location: homepage.php");
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "E-mail não encontrado.";
    }
}

// Fechar conexão (opcional dependendo da sua necessidade)
$conn = null;
?>
