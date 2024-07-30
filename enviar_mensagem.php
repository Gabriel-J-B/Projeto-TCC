<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado, redireciona para a página de login se não estiver
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se a mensagem foi enviada
if (isset($_POST['mensagem']) && isset($_POST['room_id'])) {
    $user_id = $_SESSION['user_id'];
    $room_id = intval($_POST['room_id']);
    $mensagem = trim($_POST['mensagem']);

    // Insere a mensagem no banco de dados
    $query = "INSERT INTO chat (room_id, user_id, mensagem) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$room_id, $user_id, $mensagem]);

    // Redireciona de volta para a sala
    header("Location: room.php?id=" . $room_id);
    exit();
} else {
    die('Parâmetros inválidos');
}
?>