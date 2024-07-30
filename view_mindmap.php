<?php
session_start();
require 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

// Verificar se o parâmetro 'id' está presente na URL e é um número inteiro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}

// Obter o ID do mapa mental da URL
$id = $_GET['id'];

// Preparar e executar a consulta SQL para obter o mapa mental específico do usuário
$sql = "SELECT * FROM mindmaps WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Verificar se o mapa mental foi encontrado
if ($result->num_rows > 0) {
    $mindmap = $result->fetch_assoc();

    // Retornar o mapa mental como JSON
    header('Content-Type: application/json');
    echo json_encode($mindmap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
} else {
    // Caso o mapa mental não seja encontrado, retornar um erro ou mensagem apropriada
    header("HTTP/1.1 404 Not Found");
    exit;
}
?>
