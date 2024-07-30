<?php
session_start();

// Verificar se o usuário está logado, redirecionar para a página de login se não estiver
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $layout_type = $_POST['layout_type'];
    $content = $_POST['content'];
    $usuario_id = $_SESSION['user_id'];

    // Preparar e executar a inserção do mapa mental
    $sql = "INSERT INTO mindmaps (usuario_id, title, layout_type, content) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $usuario_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $title, PDO::PARAM_STR);
    $stmt->bindValue(3, $layout_type, PDO::PARAM_STR);
    $stmt->bindValue(4, $content, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: homepage.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Mapa Mental</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: none;
        }
        textarea {
            height: 150px;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .mindmap-container {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            display: none;
            min-height: 300px;
            position: relative;
        }
        .radial .central-node {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .radial .node {
            position: absolute;
            width: 200px;
            height: 100px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: move;
        }
        .hierarchical .node {
            display: inline-block;
            width: auto;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 10px;
        }
        .freeform .content {
            white-space: pre-wrap;
            font-size: 16px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Criar Mapa Mental</h1>
        <form action="" method="POST" onsubmit="return saveMindmap()">
            <div>
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="layout_type">Tipo de Layout:</label>
                <select id="layout_type" name="layout_type" onchange="renderPreview()" required>
                    <option value="radial">Radial</option>
                    <option value="hierarchical">Hierárquico</option>
                    <option value="freeform">Livre</option>
                </select>
            </div>
            <div>
                <label for="content">Conteúdo:</label>
                <textarea id="content" name="content" oninput="renderPreview()" required></textarea>
            </div>
            <button type="submit">Salvar</button>
        </form>

        <div id="mindmap-preview" class="mindmap-container">
            <!-- Pré-visualização do mapa mental será renderizada aqui -->
        </div>
    </div>

    <script>
        function renderPreview() {
            const layoutType = document.getElementById('layout_type').value;
            const content = document.getElementById('content').value;
            const previewContainer = document.getElementById('mindmap-preview');

            previewContainer.innerHTML = ''; // Limpar o conteúdo anterior
            previewContainer.style.display = 'block'; // Exibir o contêiner

            if (layoutType === 'radial') {
                previewContainer.classList = 'mindmap-container radial';
                const centralNode = document.createElement('div');
                centralNode.className = 'central-node';
                centralNode.innerText = content;
                previewContainer.appendChild(centralNode);
            } else if (layoutType === 'hierarchical') {
                previewContainer.classList = 'mindmap-container hierarchical';
                const nodes = content.split('\n');
                nodes.forEach(nodeContent => {
                    if (nodeContent.trim() !== '') {
                        const node = document.createElement('div');
                        node.className = 'node';
                        node.innerText = nodeContent.trim();
                        previewContainer.appendChild(node);
                    }
                });
            } else if (layoutType === 'freeform') {
                previewContainer.classList = 'mindmap-container freeform';
                const freeformContent = document.createElement('div');
                freeformContent.className = 'content';
                freeformContent.innerText = content;
                previewContainer.appendChild(freeformContent);
            }
        }

        function saveMindmap() {
            const title = document.getElementById('title').value;
            const layoutType = document.getElementById('layout_type').value;
            const content = document.getElementById('content').value;

            if (!title || !layoutType || !content) {
                alert('Por favor, preencha todos os campos.');
                return false;
            }

            // Aqui você pode enviar os dados para o servidor ou fazer o que for necessário
            console.log('Título:', title);
            console.log('Tipo de Layout:', layoutType);
            console.log('Conteúdo:', content);

            return true;
        }
    </script>
</body>
</html>