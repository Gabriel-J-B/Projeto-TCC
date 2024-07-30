<?php
session_start();
require 'conexao.php';

// Verificar se o formulário foi enviado corretamente via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se todos os campos necessários foram enviados
    if (isset($_POST['room_id'], $_POST['nome'], $_POST['descricao']) && !empty($_FILES['documento'])) {
        $room_id = $_POST['room_id'];
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $documento = $_FILES['documento'];

        // Verificar se houve erro no upload do arquivo
        if ($documento['error'] === UPLOAD_ERR_OK) {
            $diretorioDestino = 'uploads/';
            $caminhoDestino = $diretorioDestino . basename($documento['name']);

            // Mover o arquivo para o diretório de destino
            if (move_uploaded_file($documento['tmp_name'], $caminhoDestino)) {
                // Preparar e executar a inserção no banco de dados
                $query = "INSERT INTO documentos (room_id, nome, descricao, caminho) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bindValue(1, $room_id, PDO::PARAM_INT);
                $stmt->bindValue(2, $nome, PDO::PARAM_STR);
                $stmt->bindValue(3, $descricao, PDO::PARAM_STR);
                $stmt->bindValue(4, $caminhoDestino, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    // Definir mensagem de sucesso na sessão
                    $_SESSION['success_message'] = "Documento '$nome' adicionado com sucesso.";

                    // Redirecionar de volta para a página da sala
                    header("Location: room.php?id=$room_id");
                    exit();
                } else {
                    // Definir mensagem de erro na sessão
                    $_SESSION['error_message'] = 'Erro ao adicionar o documento ao banco de dados.';
                }
            } else {
                // Definir mensagem de erro na sessão
                $_SESSION['error_message'] = 'Erro ao mover o arquivo para o diretório de destino.';
            }
        } else {
            // Definir mensagem de erro na sessão
            $_SESSION['error_message'] = 'Erro no upload do arquivo.';
        }
    } else {
        // Definir mensagem de erro na sessão se algum campo estiver faltando
        $_SESSION['error_message'] = 'Todos os campos são obrigatórios.';
    }

    // Redirecionar de volta para a página da sala em caso de erro
    header("Location: room.php?id=$room_id");
    exit();
} else {
    // Se o método de requisição não for POST, redirecionar para room.php
    $_SESSION['error_message'] = 'Método de requisição inválido.';
    header('Location: room.php');
    exit();
}
?>