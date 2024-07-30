<?php
require 'conexao.php';

// Verificar se o parâmetro 'id' está presente na URL e é um número inteiro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID da sala não especificado ou inválido']);
    exit;
}

// Obter o ID da sala da URL
$room_id = intval($_GET['id']); // Converter para inteiro seguro

// Buscar participantes da sala com seus respectivos status
$participants = [];
$selectParticipantsQuery = "SELECT u.id, u.nome, u.imagem, u.status 
                           FROM usuarios u 
                           INNER JOIN user_rooms ur ON u.id = ur.user_id 
                           WHERE ur.room_id = ?";
$stmt = $conn->prepare($selectParticipantsQuery);
$stmt->bindValue(1, $room_id, PDO::PARAM_INT);
$stmt->execute();
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($participants);
?>