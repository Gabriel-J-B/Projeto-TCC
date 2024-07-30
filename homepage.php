<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verifica se o usuário está logado, redireciona para a página de login se não estiver
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obter o ID do usuário logado
$user_id = $_SESSION['user_id'];

// Conexão com o banco de dados
include "conexao.php";

// Atualizar todos os usuários que não têm imagem para definir a imagem padrão 'guest.jpg'
$updateQuery = "UPDATE usuarios SET imagem = 'guest.jpg' WHERE imagem IS NULL OR imagem = ''";
$conn->exec($updateQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="C:\Users\tr3s4\Downloads\logo.jpg">
    <title>EDUC Co-Laborare - Homepage</title>
    <style>
        /* CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0; /* Remove margens padrão do body */
            padding: 20px; /* Espaçamento interno para o body */
        }

        #fundo {
            max-width: 900px;
            max-height: 600px;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9); /* Transparente */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 0 auto; /* Alinha no centro */
            overflow: auto; /* Para permitir rolagem se o conteúdo exceder a altura máxima */
            position: relative; /* Para posicionamento absoluto dentro do fundo */
        }

        #fundo::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('back login.gif') no-repeat center center/cover; /* Adiciona a GIF */
            opacity: 1; /* Ajuste a opacidade conforme necessário */
            z-index: -1; /* Coloca a imagem atrás do conteúdo */
            border-radius: 10px; /* Mantém o arredondamento */
        }

        /* Estilos para o menu */
        .menu-container {
            border-radius: 7px;
            width: 45px;
            height: 0px;
            position: absolute;
            top: 200px; /* Ajuste conforme necessário */
            right: 530px; /* Ajuste conforme necessário */
        }

        .seta {
            color: black;
            font-size: 24px;
            position: absolute;
            top: 50%;
            left: -30px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .menu {
            display: flex;
            flex-direction: column;
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: black; /* Cor de fundo do menu */
            position: absolute;
            top: 100%; /* Alinha o menu abaixo da seta */
            left: 0;
            width: 200px; /* Largura do menu */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Sombra para efeito de destaque */
            border-radius: 5px; /* Cantos arredondados */
            overflow: hidden; /* Esconde os itens fora do menu */
            transition: max-height 0.3s ease; /* Animação para abrir/fechar o menu */
            max-height: 0; /* Oculta o menu por padrão */
        }

        .menu-visivel {
            max-height: 200px; /* Altura máxima do menu quando visível */
        }

        .menu-item {
            padding: 10px 20px; /* Espaçamento interno dos itens */
            color: white;
            text-align: center; /* Alinhamento centralizado */
            transition: background-color 0.3s; /* Animação de mudança de cor */
        }

        .menu-item:hover {
            background-color: #333; /* Cor de fundo ao passar o mouse */
        }

        /* Estilos para o restante da página */
        .logo-menu {
            margin-right:450px;
            margin-top: 80px; /* Ajuste conforme necessário */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-menu img {
            width: 120px;
            height: 120px;
            height: auto;
            border-radius: 50%;
        }

        .user-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            gap: 20px; /* Espaço entre os itens */
        }

        .user-photo img {
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 120px;
            height: 120px;
            margin-left:450px;
            margin-top:-140px;
        }

        .user-details h3 {
            margin-left:565px;
            margin-top: -25px;
        }

        .user-details p {
            margin-left:565px;
            margin-top: 4px;
        }

        .options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
            margin-left: 318px;
            margin-top: 45px; /* Adiciona espaçamento superior */
        }

        .card {
            height: 220px;
            width: 150px;
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            font-size: 24px;
            color: #fff;
        }

        /* Mobile Responsivo */
        @media (max-width: 768px) {
            .logo-menu {
                flex-direction: column;
                gap: 10px;
            }

            .user-info {
                flex-direction: column;
                align-items: center;
            }

            .user-photo img {
                margin-bottom: 10px;
            }

            .options {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <section id="fundo">
        <div class="container">
            <div class="logo-menu">
                <img src="logo.jpg" alt="Logo da Plataforma">
            </div>
        </div>
        <div class="menu-container">
            MENU
            <div class="seta">▶</div> 
            <div class="menu">
                <!-- Itens do menu -->
                <a href="index.php" style="text-decoration: none;"><div class="menu-item">Sobre</div></a>
                <a href="logout.php" style="text-decoration: none;"><div class="menu-item">Logout</div></a>
            </div>
        </div>

        <div class="user-info">
            <div class="user-photo">
                <?php
                // Query para recuperar o caminho da imagem do usuário
                $id_usuario = $_SESSION['user_id'];
                $sql = "SELECT imagem FROM usuarios WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id_usuario);

                // Executa a consulta SQL e verifica erros
                if ($stmt->execute()) {
                    $caminho_imagem = $stmt->fetchColumn();
                    if ($caminho_imagem) {
                        // Verifica se o caminho da imagem é válido
                        if (file_exists($caminho_imagem)) {
                            echo '<img src="' . $caminho_imagem . '" alt="Foto do Usuário" width="150">';
                        } else {
                            echo '<img src="guest.jpg" alt="Guest" width="150">';
                        }
                    } else {
                        echo '<img src="guest.jpg" alt="Guest" width="150">';
                    }
                } else {
                    echo "Erro na consulta SQL: " . $stmt->errorInfo()[2];
                }
                ?>
            </div>
        </div>
        
        <div class="user-details">
            <?php
            // Query para recuperar o nome e a tag do usuário
            $id_usuario = $_SESSION['user_id'];
            $sql = "SELECT nome, tag FROM usuarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id_usuario);

            // Executa a consulta SQL e verifica erros
            if ($stmt->execute()) {
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($resultado) {
                    echo '<h3>' . $resultado['nome'] . '</h3>';
                    echo '<p>' . $resultado['tag'] . '</p>';
                } else {
                    echo '<h3>Nome do Usuário</h3>';
                    echo '<p>Tag do Usuário</p>';
                }
            } else {
                echo "Erro na consulta SQL: " . $stmt->errorInfo()[2];
            }
            ?>
        </div>
    </div>

    <main>
        <section class="options">
            <div class="card" onclick="location.href='view_perfil.php';">
                <h3>Perfil</h3>
            </div>
            <div class="card" onclick="location.href='salas.php';">
                <h3>Salas</h3>
            </div>
        </section>
    </main>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const seta = document.querySelector('.seta');
            const menu = document.querySelector('.menu');

            seta.addEventListener('click', function() {
                menu.classList.toggle('menu-visivel');
            });
        });

</script>
</body>
</html>
