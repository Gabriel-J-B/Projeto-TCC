<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado, redireciona para a página de login se não estiver
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obter o ID do usuário logado
$user_id = $_SESSION['user_id'];

// Receber o nome ou ID da sala por POST, se não estiver vazio
$roomInput = $_POST['roomName'] ?? '';

// Se o método de requisição for POST e o nome da sala não estiver vazio, processar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($roomInput)) {
    // Verifica se o input é um número (ID da sala)
    if (is_numeric($roomInput)) {
        // Verificar se a sala com o ID fornecido existe
        $query = "SELECT id, name FROM rooms WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $roomInput, PDO::PARAM_INT);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($room) {
            // Sala com o ID existe, adicionar o usuário à sala
            $room_id = $room['id'];
            $query = "INSERT INTO user_rooms (user_id, room_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE room_id = room_id";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $room_id, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // Sala com o ID não existe
            echo "<script>alert('Sala não encontrada.'); window.location.href = 'salas.php';</script>";
            exit();
        }
    } else {
        // Verificar se a sala com o nome fornecido já existe
        $query = "SELECT id, name FROM rooms WHERE name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $roomInput, PDO::PARAM_STR);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($room) {
            // Sala com o nome existe, adicionar o usuário à sala
            $room_id = $room['id'];
            $query = "INSERT INTO user_rooms (user_id, room_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE room_id = room_id";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $room_id, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // Criar uma nova sala com o nome fornecido
            $query = "INSERT INTO rooms (name) VALUES (?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $roomInput, PDO::PARAM_STR);
            $stmt->execute();
            $room_id = $conn->lastInsertId();
            
            // Adicionar o usuário à nova sala
            $query = "INSERT INTO user_rooms (user_id, room_id) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $room_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    
    // Redirecionar para a página da sala
    header("Location: room.php?id=$room_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f0f0f0;
    }

    .container {
        width: 90%;
        max-width: 600px;
        background: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    header h1 {
        margin: 0;
    }

    #addRoomBtn {
        font-size: 24px;
        width: 40px;
        height: 40px;
        border: none;
        background: #007bff;
        color: #fff;
        border-radius: 50%;
        cursor: pointer;
    }

    #roomsList {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .room {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #fafafa;
        cursor: pointer;
        transition: background 0.3s;
    }

    .room:hover {
        background: #f1f1f1;
    }

    .link-homepage {
        position: absolute;
        margin-left: 160px;
        margin-top: 350px;
        color: #007bff;
        text-decoration: underline;
        cursor: pointer;
        transition: color 0.3s;
    }

    .link-homepage:hover {
        color: #0056b3;
    }
</style>

<body>
    <div class="container">
        <header>
            <h1>Minhas Salas</h1>
            <button id="addRoomBtn">+</button>
        </header>
        <main id="roomsList">
            <?php
            // Buscar as salas do usuário no banco de dados
            $query = "SELECT rooms.id, rooms.name FROM rooms 
                    JOIN user_rooms ON rooms.id = user_rooms.room_id 
                    WHERE user_rooms.user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rooms as $room) {
                echo '<div class="room" onclick="enterRoom(' . $room['id'] . ')">' . htmlspecialchars($room['name']) . '</div>';
            }
            ?>
        </main>
    </div>

    <p class="link-homepage" onclick="goToHomepage()">Voltar para a Homepage</p>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addRoomBtn = document.getElementById('addRoomBtn');

            // Função para entrar em uma sala
            window.enterRoom = function(roomId) {
                window.location.href = `room.php?id=${roomId}`;
            };

            // Função para criar ou entrar em uma nova sala
            addRoomBtn.addEventListener('click', () => {
                const roomInput = prompt('Digite o nome da nova sala ou o ID de uma sala existente:');
                if (roomInput) {
                    createOrJoinRoom(roomInput);
                }
            });

            // Função para criar ou entrar em uma sala usando uma chamada AJAX
            function createOrJoinRoom(roomInput) {
                const form = document.createElement('form');
                form.method = 'post';
                form.action = 'salas.php';
                form.style.display = 'none';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'roomName';
                input.value = roomInput;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });

        function goToHomepage() {
            window.location.href = 'homepage.php';
        };
    </script>
</body>
</html>