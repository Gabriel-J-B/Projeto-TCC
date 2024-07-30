<?php
// Configurações para exibir erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sessão
session_start();

// Verifica se o usuário está logado, redireciona para a página de login se não estiver
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obter o ID do usuário logado
$user_id = $_SESSION['user_id'];

// Incluir arquivo de conexão com o banco de dados
include "conexao.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="C:\Users\tr3s4\Downloads\logo.jpg">
    <title>EDUC Co-Laborare - Perfil</title>
    <style>
        /* Reset de margin e padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilo do corpo da página */
        body {
            font-family: Arial, sans-serif;
            background-image: url("efficient_wallpaper.gif");
            background-size: cover; /* Cobrir toda a página */
            background-repeat: no-repeat; /* Evitar repetições */
            margin: 0;
            padding: 0;
        }

        /* Estilo do contêiner principal */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Estilo do cabeçalho */
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative; /* Adicionado para alinhar o ícone */
        }

        .header h1 {
            color: #fff;
            font-size: 32px;
            margin-bottom: 10px;
        }

        /* Ícone para voltar à página inicial */
        .back-to-home {
            position: absolute;
            left: 20px;
            top: 20px;
            color: blue;
            text-decoration: none;
        }

        /* Estilo da seção de perfil */
        .profile-section {
            background-color: rgba(255, 255, 255, 0.8); /* Transparente */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile-section h2 {
            color: #fff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .profile-section #h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Estilo dos detalhes do usuário */
        .user-details {
            list-style: none;
            padding: 0;
        }

        .user-details li {
            margin-bottom: 10px;
        }

        .user-details strong {
            font-weight: bold;
            color: #555;
        }

        /* Estilo da imagem do usuário */
        .user-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background-image: url('caminho_para_imagem_do_usuario.jpg'); /* Substitua pelo caminho real da imagem do usuário */
            background-size: cover;
            background-position: center;
        }

        /* Estilo do botão de editar perfil */
        .edit-profile-button {
            display: block;
            width: 100%;
            background-color: #007bff; /* Azul */
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .edit-profile-button:hover {
            background-color: #0056b3; /* Azul mais escuro */
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Cabeçalho -->
    <div class="header">
        <!-- Ícone para voltar à página inicial -->
        <a href="homepage.php" class="back-to-home">Voltar para a Homepage</a>
        <h1>Perfil do Usuário</h1>
    </div>

    <!-- Seção de perfil -->
    <section class="profile-section">
        <!-- Imagem do usuário -->
        <div class="user-image">
            <!-- Aqui vai a imagem do usuário -->
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
        </div>

        <!-- Dados do usuário -->
        <h2 id="h2">Dados do Usuário</h2>
        <ul class="user-details">
            <?php
            // Consulta SQL para obter os dados do usuário
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                // Exibir os detalhes do usuário
                echo "<li><strong>Nome:</strong> " . $result["nome"] . "</li>";
                echo "<li><strong>E-mail:</strong> " . $result["email"] . "</li>";
                echo "<li><strong>Tag:</strong> " . $result["tag"] . "</li>";
                echo "<li><strong>Estado:</strong> " . $result["estado"] . "</li>";
                echo "<li><strong>Cidade:</strong> " . $result["cidade"] . "</li>";
                echo "<li><strong>Escola:</strong> " . $result["escola"] . "</li>";
            } else {
                echo "<li>Nenhum dado encontrado.</li>";
            }
            ?>
        </ul>
        <!-- Botão de editar perfil -->
        <a href="perfil.php" class="edit-profile-button">Editar Perfil</a>
    </section>

</div>

</body>
</html>
