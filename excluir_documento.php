<?php
session_start();
require 'conexao.php';

// Verificar se o parâmetro 'id' está presente na URL
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = 'ID do documento não especificado';
    header('Location: room.php');
    exit;
}

// Obter o ID do documento da URL
$document_id = $_GET['id'];

// Buscar as informações do documento no banco de dados
$query = "SELECT nome, room_id FROM documentos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bindValue(1, $document_id, PDO::PARAM_INT);
$stmt->execute();
$document = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    $_SESSION['error_message'] = 'Documento não encontrado';
    header('Location: room.php');
    exit;
}

$documentName = $document['nome'];
$room_id = $document['room_id'];

// Excluir o documento do banco de dados
$deleteQuery = "DELETE FROM documentos WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bindValue(1, $document_id, PDO::PARAM_INT);
$result = $stmt->execute();

if ($result) {
    $_SESSION['success_message'] = "Documento '$documentName' foi excluído com sucesso.";
} else {
    $_SESSION['error_message'] = "Erro ao excluir o documento '$documentName'.";
}

// Redirecionar para room.php após a exclusão
header("Location: room.php?id=$room_id");
exit;
?>
