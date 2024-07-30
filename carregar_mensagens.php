<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Status de acesso negado
    echo json_encode(['error' => 'Acesso não autorizado.']);
    exit();
}

// Verifica se foi fornecido o ID da sala via GET
if (!isset($_GET['room_id']) || !is_numeric($_GET['room_id'])) {
    http_response_code(400); // Status de solicitação inválida
    echo json_encode(['error' => 'ID da sala não especificado ou inválido']);
    exit();
}

$room_id = intval($_GET['room_id']);

try {
    // Consulta para buscar as mensagens da sala
    $query = "SELECT c.mensagem, c.created_at, u.nome 
              FROM chat c 
              INNER JOIN usuarios u ON c.user_id = u.id 
              WHERE c.room_id = ? 
              ORDER BY c.created_at ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$room_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna as mensagens como JSON
    header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON
    echo json_encode($messages);
} catch (PDOException $e) {
    http_response_code(500); // Status de erro interno do servidor
    echo json_encode(['error' => 'Erro de banco de dados: ' . $e->getMessage()]);
    exit();
}
?>
