<?php
// Incluir o arquivo de conexão
include 'conexao.php';

// Verificar se o método de requisição é DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Método não permitido
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Verificar se o ID do mapa mental foi fornecido via parâmetro GET
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    http_response_code(400); // Requisição inválida
    echo json_encode(['error' => 'ID do mapa mental não fornecido']);
    exit;
}

// Preparar a declaração SQL para deletar o mapa mental
$stmt = $mysqli->prepare("DELETE FROM mapas_mentais WHERE id = ?");
$stmt->bind_param('i', $id);

// Executar a declaração
if ($stmt->execute()) {
    // Exclusão bem-sucedida
    echo json_encode(['message' => 'Mapa mental excluído com sucesso']);
} else {
    // Erro ao deletar
    http_response_code(500); // Erro interno do servidor
    echo json_encode(['error' => 'Erro ao excluir mapa mental: ' . $stmt->error]);
}

// Fechar declaração
$stmt->close();

// Redirecionar para room.php após a exclusão
header('Location: room.php');
exit; // Certifique-se de sair após o redirecionamento para evitar execução adicional do código
?>