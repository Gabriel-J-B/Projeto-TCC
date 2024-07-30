<?php
session_start();
require 'conexao.php';

// Verificar se o parâmetro 'id' está presente na URL
if (!isset($_GET['id'])) {
    die('ID do documento não especificado');
}

// Obter o ID do documento da URL
$documento_id = $_GET['id'];

// Verificar se o ID da sala está presente na sessão
if (!isset($_SESSION['sala_id'])) {
    die('ID da sala não especificado na sessão');
}

$sala_id = $_SESSION['sala_id'];

// Função para obter extensão do arquivo
function getFileExtension($filename) {
    return pathinfo($filename, PATHINFO_EXTENSION);
}

// Buscar informações do documento no banco de dados
$query = "SELECT nome, descricao, caminho FROM documentos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bindValue(1, $documento_id, PDO::PARAM_INT);
$stmt->execute();
$documento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$documento) {
    die('Documento não encontrado');
}

$nome_documento = $documento['nome'];
$descricao_documento = $documento['descricao'];
$caminho_documento = $documento['caminho'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Documento: <?php echo htmlspecialchars($nome_documento); ?></title>
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

        .document-viewer {
            margin-bottom: 20px;
        }

        .document-info {
            margin-bottom: 20px;
        }

        .document-info h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .document-info p {
            font-size: 16px;
            color: #666;
        }

        .viewer-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .viewer-container img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .back-button {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="document-viewer">
            <div class="viewer-container">
                <!-- Exibir o documento (imagem, PDF, etc.) -->
                <?php
                if (getFileExtension($caminho_documento) == 'pdf') {
                    echo '<embed src="' . htmlspecialchars($caminho_documento) . '" type="application/pdf" width="100%" height="600px" />';
                } else {
                    echo '<img src="' . htmlspecialchars($caminho_documento) . '" alt="' . htmlspecialchars($nome_documento) . '" />';
                }
                ?>
            </div>
        </div>

        <div class="document-info">
            <h2><?php echo htmlspecialchars($nome_documento); ?></h2>
            <p><?php echo htmlspecialchars($descricao_documento); ?></p>
        </div>

        <a href="room.php?id=<?php echo htmlspecialchars($sala_id); ?>" class="back-button">Voltar para Room</a>
</body>
</html>