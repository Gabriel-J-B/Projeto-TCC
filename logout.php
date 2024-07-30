<?php
session_start();
require 'conexao.php'; // Verifique se o arquivo de conexão está correto

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

// Obter o ID do usuário da sessão
$user_id = $_SESSION['user_id'];

// Atualizar o status do usuário para offline no banco de dados
$status = 'offline';
$sql = "UPDATE usuarios SET status = :status WHERE id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$result = $stmt->execute();

if ($result) {
    // Limpar todas as variáveis de sessão
    $_SESSION = array();

    // Invalidar a sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalmente, destruir a sessão
    session_destroy();

    // Redirecionar para a página de login ou outra página após o logout
    header("Location: login.php");
    exit;
} else {
    echo "Erro ao atualizar status do usuário.";
}
?>
