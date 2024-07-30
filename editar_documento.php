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
$documento_id = $_GET['id'];

// Verificar se o ID da sala está presente na sessão
if (!isset($_SESSION['sala_id'])) {
    $_SESSION['error_message'] = 'ID da sala não especificado na sessão';
    header('Location: room.php');
    exit;
}

$sala_id = $_SESSION['sala_id'];

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber e validar os dados do formulário
    $nome_documento = $_POST['nome'] ?? '';
    $descricao_documento = $_POST['descricao'] ?? '';

    // Verificar se um novo documento foi enviado
    if ($_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['documento']['name']);
        
        // Mover o novo documento para o diretório de upload
        if (move_uploaded_file($_FILES['documento']['tmp_name'], $uploadFile)) {
            // Atualizar o caminho do documento no banco de dados
            $queryUpdate = "UPDATE documentos SET nome = ?, descricao = ?, caminho = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($queryUpdate);
            $stmtUpdate->bindValue(1, $nome_documento, PDO::PARAM_STR);
            $stmtUpdate->bindValue(2, $descricao_documento, PDO::PARAM_STR);
            $stmtUpdate->bindValue(3, $uploadFile, PDO::PARAM_STR);
            $stmtUpdate->bindValue(4, $documento_id, PDO::PARAM_INT);
            $result = $stmtUpdate->execute();

            if ($result) {
                $_SESSION['success_message'] = "Documento atualizado com sucesso.";
            } else {
                $_SESSION['error_message'] = "Erro ao atualizar o documento.";
            }
        } else {
            $_SESSION['error_message'] = "Erro ao fazer upload do documento.";
        }
    } else {
        // Apenas atualizar nome e descrição (sem novo documento)
        $queryUpdate = "UPDATE documentos SET nome = ?, descricao = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindValue(1, $nome_documento, PDO::PARAM_STR);
        $stmtUpdate->bindValue(2, $descricao_documento, PDO::PARAM_STR);
        $stmtUpdate->bindValue(3, $documento_id, PDO::PARAM_INT);
        $result = $stmtUpdate->execute();

        if ($result) {
            $_SESSION['success_message'] = "Documento atualizado com sucesso.";
        } else {
            $_SESSION['error_message'] = "Erro ao atualizar o documento.";
        }
    }

    // Redirecionar de volta para a página da sala
    header("Location: room.php?id=$sala_id");
    exit;
}

// Buscar informações atuais do documento no banco de dados
$query = "SELECT nome, descricao FROM documentos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bindValue(1, $documento_id, PDO::PARAM_INT);
$stmt->execute();
$documento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$documento) {
    $_SESSION['error_message'] = 'Documento não encontrado';
    header('Location: room.php');
    exit;
}

$nome_documento = $documento['nome'];
$descricao_documento = $documento['descricao'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Documento: <?php echo htmlspecialchars($nome_documento); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .form-container form {
            margin-top: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-container input[type="text"],
        .form-container textarea {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .form-container input[type="file"] {
            margin-top: 10px;
        }

        .form-container button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Editar Documento: <?php echo htmlspecialchars($nome_documento); ?></h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $documento_id; ?>" method="POST" enctype="multipart/form-data">
                <label for="nome">Nome do Documento:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome_documento); ?>" required>

                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4"><?php echo htmlspecialchars($descricao_documento); ?></textarea>

                <label for="documento">Substituir Documento:</label>
                <input type="file" id="documento" name="documento">

                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </div>
</body>
</html>
