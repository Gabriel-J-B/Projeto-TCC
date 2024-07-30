<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado, redireciona para a página de login se não estiver
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar se o parâmetro 'id' está presente na URL e é um número inteiro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID da sala não especificado ou inválido');
}

// Obter o ID do usuário logado
$user_id = $_SESSION['user_id'];

// Obter o ID da sala da URL
$room_id = intval($_GET['id']); // Converter para inteiro seguro

// Armazenar o ID da sala na sessão
$_SESSION['sala_id'] = $room_id;

// Buscar as informações da sala no banco de dados
$query = "SELECT name FROM rooms WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bindValue(1, $room_id, PDO::PARAM_INT);
$stmt->execute();
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die('Sala não encontrada');
}

$roomName = htmlspecialchars($room['name']); // Sanitizar o nome da sala para exibição segura

// Inicializar a variável $documents como um array vazio
$documents = [];

// Buscar documentos associados à sala
$selectDocumentsQuery = "SELECT id, nome, descricao FROM documentos WHERE room_id = ?";
$stmt = $conn->prepare($selectDocumentsQuery);
$stmt->bindValue(1, $room_id, PDO::PARAM_INT);
$stmt->execute();
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

// Buscando mapas mentais do usuário
$mindmaps = [];
$usuario_id = $_SESSION['user_id'];
$sql = "SELECT * FROM mindmaps WHERE usuario_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$usuario_id]);
$mindmaps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala: <?php echo htmlspecialchars($roomName); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-m/Z9QZemA0P+4fvOIjn6OLWjwBm0M7/ttUzvsa+0aZoVdNmvERHq2Is6ntNJo/FCG0eAZKgA3Tq8Q5h9zz5OvQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        main {
            display: flex;
            flex-wrap: wrap; /* Permite que as seções sejam dispostas em várias linhas */
            gap: 20px; /* Espaçamento entre as seções */
            padding: 20px; /* Preenchimento interno do main */
        }

        .section {
            flex-grow: 1; /* Faz com que as seções cresçam para ocupar o espaço disponível */
            background: #fafafa;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box; /* Garante que o padding não afete o dimensionamento total */
        }

        .section h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .action-icon {
            font-size: 20px;
            color: #007bff;
            cursor: pointer;
        }

        .documents-list {
            margin-top: 10px;
            max-height: 400px;
            overflow-y: auto;
        }

        .document {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .document:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .document h3 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 16px;
            color: #333;
        }

        .document p {
            color: #666;
            margin-bottom: 10px;
        }

        .document-actions {
            margin-top: 5px;
        }

        .document-actions button {
            margin-right: 10px;
            padding: 6px 12px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-view {
            background-color: #28a745;
            color: #fff;
        }

        .btn-edit {
            background-color: #007bff;
            color: #fff;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }

        .add-document-form {
            margin-top: 20px;
        }

        .add-document-form input[type="text"],
        .add-document-form textarea,
        .add-document-form input[type="file"] {
            width: calc(100% - 24px);
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .add-document-form button[type="submit"] {
            width: calc(100% - 24px);
            padding: 8px 20px;
            font-size: 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-document-form button[type="submit"]:hover{
            background-color: #008bff;
        }

        .documents-section, .mindmap-section {
            width: calc(50% - 10px); /* Largura de 50% menos a metade do gap entre as seções */
        }

        .chat-section {
            width: 100%; /* Largura total dentro do main */
            order: 3; /* Define a ordem de exibição, colocando o chat como terceiro */
        }

        .participantes {
            margin-left:14px;
            position: relative;
            width: 30%; /* Largura da barra lateral */
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-radius: 8px;
            overflow: hidden;
        }

        .sidebar {
            width: 90%; /* Largura total da barra lateral */
            background: #f0f0f0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .participants {
            margin-bottom: 20px;
        }

        .participant {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .participant img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .participant-info {
            display: flex;
            align-items: center; /* Centraliza verticalmente o conteúdo */
        }


        .participant-name {
            font-weight: bold;
            margin-right: 10px; /* Espaço entre o nome e o status */
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            text-align: center;
        }

        .status.online {
            background: #4CAF50;
            color: #fff;
        }

        .status.offline {
            background: #ccc;
            color: #555;
        }

        .alert {
            position: fixed;
            top: 10px; /* Ajuste conforme necessário */
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 600px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .alert-success {
            background-color: #28a745;
            color: #fff;
        }

        .alert-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .redirect {
            position: fixed;
            left: 50%;
            bottom: 20px; 
            transform: translateX(-50%);
        }

        .mindmap-container {
            margin-top: 20px;
            border: 1px solid #000;
            padding: 10px;
        }
        .radial .central-node {
            text-align: center;
        }
        .hierarchical .node {
            margin-left: 20px;
        }

        /* Estilo para o botão */
        .btn-create-mindmap {
            margin-top: 136.5px;
            display: inline-block;
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .btn-create-mindmap:hover {
            background-color: #008bff;
        }

        #messages-container {
            max-height: 400px; /* Altura máxima da div */
            overflow-y: auto; /* Habilita rolagem vertical */
            border: 1px solid #ccc; /* Borda para a div */
            padding: 10px; /* Espaçamento interno */
        }

        .message {
            background-color: #f0f0f0; /* Cor de fundo das mensagens */
            padding: 5px; /* Espaçamento interno das mensagens */
            margin-bottom: 5px; /* Espaçamento entre mensagens */
            border-radius: 5px; /* Borda arredondada */
        }

        @media screen and (max-width: 768px) {
            .participantes {
                width: 100%; /* Altera para largura total em telas menores */
                margin-top: 20px;
            }

            .section {
                width: 100%; /* Largura total em telas menores */
            }

            .documents-section, .mindmap-section {
                width: 100%; /* Largura total em telas menores */
                margin-bottom: 20px; /* Espaçamento entre seções */
            }

            .chat-section {
                order: 2; /* Coloca o chat em segundo lugar em telas menores */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Bem-vindo à sala: <?php echo htmlspecialchars($roomName); ?></h1>
        </header>

        <!-- Exibição da mensagem de sucesso ou erro -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <main>
            <section class="documents-section section">
                <h2>Documentos <i class="fas fa-plus-circle action-icon"></i></h2>
                <div class="documents-list">
                    <?php if (count($documents) > 0): ?>
                        <?php foreach ($documents as $document): ?>
                            <div class="document">
                                <h3><?php echo htmlspecialchars($document['nome']); ?></h3>
                                <p><?php echo htmlspecialchars($document['descricao']); ?></p>
                                <div class="document-actions">
                                    <button class="btn btn-view" onclick="visualizarDocumento(<?php echo $document['id']; ?>)">Visualizar</button>
                                    <button class="btn btn-edit" onclick="editarDocumento(<?php echo $document['id']; ?>)">Editar</button>
                                    <button class="btn btn-delete" onclick="excluirDocumento(<?php echo $document['id']; ?>)">Excluir</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhum documento adicionado ainda.</p>
                    <?php endif; ?>
                </div>
                <form class="add-document-form" action="upload_documento.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                    <input type="text" name="nome" placeholder="Nome do documento" required>
                    <textarea name="descricao" rows="3" placeholder="Descrição do documento"></textarea>
                    <input type="file" name="documento" required>
                    <button type="submit">Adicionar Documento</button>
                </form>
            </section>

            <section class="mindmap-section section">
                <h2>Mapa Mental <i class="fas fa-plus-circle action-icon"></i></h2>
                <?php foreach ($mindmaps as $mindmap): ?>
                <div>
                    <h3><?php echo htmlspecialchars($mindmap['title']); ?></h3>
                    <p>Layout: <?php echo htmlspecialchars($mindmap['layout_type']); ?></p>
                    <button onclick="viewMindmap(<?php echo $mindmap['id']; ?>)">Visualizar</button>
                    <button onclick="deleteMindmap(<?php echo $mindmap['id']; ?>)">Excluir</button> <!-- Botão de exclusão -->
                </div>
                <?php endforeach; ?>

                <a href="create_mindmap.php" class="btn-create-mindmap">Criar Novo Mapa Mental</a>
            </section>

            <div id="mindmap-display" style="display: none;">
                <h2 id="mindmap-title"></h2>
                <div id="mindmap-content"></div>
                <button onclick="closeMindmap()">Fechar</button>
            </div>

            <section class="chat-section section">
                <div class="chat-messages" id="chat-messages"></div>
                <form id="message-form" action="enviar_mensagem.php" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                    <input type="text" id="mensagem" name="mensagem" placeholder="Digite sua mensagem..." required>
                    <button type="submit">Enviar</button>
                </form>
            </section>
        </main>
    </div>
    
    <div class="participantes">
        <aside class="sidebar">
            <div class="participants">
                <h2>Participantes</h2>
                <?php foreach ($participants as $participant): ?>
                <div class="participant">
                    <img src="<?php echo $participant['imagem']; ?>" alt="<?php echo $participant['nome']; ?>">
                    <div class="participant-info">
                        <p class="participant-name"><?php echo $participant['nome']; ?></p>
                        <span class="status <?php echo strtolower($participant['status']); ?>"><?php echo ucfirst($participant['status']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>

    <div class="redirect">
        <a href="salas.php"><p >Voltar para salas</p></a>
    </div>
    
    <script>
        function visualizarDocumento(documentoId) {
            // Redirecionar para visualizar_documento.php com o ID do documento
            window.location.href = 'visualizar_documento.php?id=' + documentoId;
        }

        function editarDocumento(documentoId) {
            // Redirecionar para editar_documento.php com o ID do documento
            window.location.href = 'editar_documento.php?id=' + documentoId;
        }

        function excluirDocumento(documentoId) {
            if (confirm('Tem certeza que deseja excluir este documento?')) {
                // Redirecionar para excluir_documento.php com o ID do documento
                window.location.href = 'excluir_documento.php?id=' + documentoId;
            }
        }
    </script>

    <script>
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000); // Fechar após 5 segundos (5000 milissegundos)
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Função para buscar e atualizar os participantes da sala
            function updateParticipants() {
                var roomId = <?php echo $room_id; ?>; // ID da sala atual
                var participantsList = document.getElementById('participants-list');

                // Verificar se a lista de participantes foi encontrada
                if (!participantsList) {
                    console.error('Elemento #participants-list não encontrado');
                    return;
                }

                // Limpar a lista de participantes antes de atualizar
                participantsList.innerHTML = '';

                // Realizar requisição AJAX para obter os participantes da sala
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_participants.php?id=' + roomId, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var participants = JSON.parse(xhr.responseText);

                        // Iterar sobre os participantes e criar elementos HTML para cada um
                        participants.forEach(function(participant) {
                            var participantDiv = document.createElement('div');
                            participantDiv.classList.add('participant');

                            var img = document.createElement('img');
                            img.src = participant.imagem || 'guest.jpg'; // Definir 'guest.jpg' se a imagem não estiver presente
                            img.alt = participant.nome;
                            participantDiv.appendChild(img);

                            var participantInfo = document.createElement('div');
                            participantInfo.classList.add('participant-info');

                            var participantName = document.createElement('p');
                            participantName.classList.add('participant-name');
                            participantName.textContent = participant.nome;
                            participantInfo.appendChild(participantName);

                            var statusSpan = document.createElement('span');
                            statusSpan.classList.add('status');
                            statusSpan.classList.add(participant.status.toLowerCase());
                            statusSpan.textContent = participant.status.charAt(0).toUpperCase() + participant.status.slice(1);
                            participantInfo.appendChild(statusSpan);

                            participantDiv.appendChild(participantInfo);
                            participantsList.appendChild(participantDiv);
                        });
                    } else {
                        console.error('Erro ao buscar participantes da sala. Status: ' + xhr.status);
                    }
                };
                xhr.onerror = function() {
                    console.error('Erro de rede ao buscar participantes da sala');
                };
                xhr.send();
            }

            // Atualizar os participantes a cada intervalo de tempo (por exemplo, a cada 10 segundos)
            setInterval(updateParticipants, 10000); // 10000 milissegundos = 10 segundos

            // Chamar a função uma vez ao carregar a página para exibir os participantes imediatamente
            updateParticipants();
        });
    </script>

    <script>
        function viewMindmap(id) {
            fetch('view_mindmap.php?id=' + id)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar mapa mental');
                }
                return response.json();
            })
            .then(data => {
                // Processar os dados do mapa mental aqui
                document.getElementById('mindmap-title').innerText = data.title;
                const contentContainer = document.getElementById('mindmap-content');
                contentContainer.innerHTML = ''; // Limpar conteúdo anterior

                const previewContainer = document.createElement('div');
                previewContainer.classList.add('mindmap-container');

                if (data.layout_type === 'radial') {
                    previewContainer.classList.add('radial');
                    const centralNode = document.createElement('div');
                    centralNode.className = 'central-node';
                    centralNode.innerText = data.content;
                    previewContainer.appendChild(centralNode);
                } else if (data.layout_type === 'hierarchical') {
                    previewContainer.classList.add('hierarchical');
                    const node = document.createElement('div');
                    node.className = 'node';
                    node.innerText = data.content;
                    previewContainer.appendChild(node);
                } else if (data.layout_type === 'freeform') {
                    previewContainer.classList.add('freeform');
                    const freeformContent = document.createElement('div');
                    freeformContent.innerHTML = data.content;
                    previewContainer.appendChild(freeformContent);
                }

                contentContainer.appendChild(previewContainer);
                document.getElementById('mindmap-display').style.display = 'block';
            })
            .catch(error => {
                console.error('Erro:', error);
                // Tratar o erro aqui, por exemplo, exibindo uma mensagem ao usuário
            });
        }

        function deleteMindmap(id) {
            fetch('delete_mindmap.php?id=' + id, {
                method: 'DELETE',
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao excluir mapa mental');
                }
                // Tentar remover o item da lista após exclusão bem-sucedida
                const mindmapItem = document.getElementById(`mindmap-item-${id}`);
                if (mindmapItem) {
                    mindmapItem.remove();
                    alert('Mapa mental excluído com sucesso!');
                } else {
                    console.error(`Elemento com ID 'mindmap-item-${id}' não encontrado.`);
                    alert('Erro ao excluir mapa mental: elemento não encontrado na página.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao excluir mapa mental. Verifique o console para mais detalhes.');
            });
        }
    </script>

    <script>
        function loadMessages() {
            var url = 'carregar_mensagens.php?room_id=<?php echo $room_id; ?>';
            
            fetch(url)
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Erro ao carregar mensagens: ' + response.status);
                    }
                    return response.json();
                })
                .then(function(data) {
                    displayMessages(data);
                })
                .catch(function(error) {
                    console.error('Erro ao carregar mensagens:', error);
                });
        }

        function displayMessages(messages) {
            var messagesContainer = document.getElementById('chat-messages');
            messagesContainer.innerHTML = '';

            messages.forEach(function(message) {
                var messageElement = document.createElement('div');
                messageElement.classList.add('message');
                messageElement.textContent = message.nome + ': ' + message.mensagem + ' (' + message.created_at + ')';
                messagesContainer.appendChild(messageElement);
            });

            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        document.getElementById('message-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var messageInput = document.getElementById('mensagem').value.trim();

            if (messageInput === '') {
                return;
            }

            var formData = new FormData();
            formData.append('room_id', <?php echo $room_id; ?>);
            formData.append('mensagem', messageInput);

            fetch('enviar_mensagem.php', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Erro ao enviar mensagem: ' + response.status);
                }
                document.getElementById('mensagem').value = '';
                loadMessages();
            })
            .catch(function(error) {
                console.error('Erro ao enviar mensagem:', error);
            });
        });

        window.onload = function() {
            loadMessages();
        };
    </script>
</body>
</html>