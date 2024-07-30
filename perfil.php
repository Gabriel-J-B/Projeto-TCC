<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="C:\Users\tr3s4\Downloads\logo.jpg">
    <title>EDUC Co-Laborare - Editar Perfil</title>
    <style>
        /* Estilos da página */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('cool_wallpaper.gif');
            background-size: cover;
            z-index: -1;
        }

        .container {
            overflow-y: auto;
            margin: 0 auto;
            max-width: 400px;
            max-height: auto;
            position: relative;
            text-align: center; /* Centraliza os elementos */
        }

        .blur-bg {
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1; /* Ajuste o z-index para colocar atrás da logo */
        }

        .logo {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
            z-index: 2; /* Ajuste o z-index para colocar na frente do blur */
            position: relative;
        }

        .logo img {
            margin-bottom: 5px;
            width: 100px;
            height: auto;
            border-radius: 50%;
            position: relative; /* Mude para relativo para ajustar dentro do container */
            display: block; /* Para ajustar centralmente */
            margin: 0 auto; /* Para centralizar horizontalmente */
        }

        /* Estilos para o formulário de edição de perfil */
        .edit-profile-form {
            margin-top: 20px;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            text-align: left; /* Alinha o texto à esquerda */
        }

        .edit-profile-form label {
            display: block;
            margin-bottom: 5px;
            margin-left: 50px;
        }

        .edit-profile-form input[type="text"],
        .edit-profile-form input[type="email"],
        .edit-profile-form input[type="password"],
        .edit-profile-form input[type="file"],
        .edit-profile-form select {
            width: calc(100% - 82px);
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-left: 50px;
            margin-bottom: 20px;
        }

        .edit-profile-form button[type="submit"] {
            margin-left: 50px;
            display: block;
            width: calc(100% - 110px);
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
        }

        .edit-profile-form button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Imagem de fundo -->
    <div class="bg-image"></div>

    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
        </div>

        <div class="blur-bg"></div> <!-- Contêiner transparente -->

        <!-- Formulário de edição de perfil -->
        <form class="edit-profile-form" action="atualizar_perfil.php" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Nova Senha:</label>
            <input type="password" id="senha" name="senha">

            <label for="estado">Estado:</label>
            <select id="estado" name="estado">
                <option value="Rio Grande do Sul">Rio Grande do Sul</option>
                <option value="NA">N/A</option>
                <!-- Adicione as opções para outros estados aqui -->
            </select>

            <label for="cidade">Cidade:</label>
            <select id="cidade" name="cidade">
                <option value="Guaíba">Guaíba</option>
                <option value="Porto Alegre">Porto Alegre</option>
                <!-- Adicione as opções para outras cidades aqui -->
            </select>

            <label for="escola">Nome da Escola:</label>
            <input type="text" id="escola" name="escola">

            <label for="imagem">Imagem de Perfil:</label>
            <input type="file" id="imagem" name="imagem">

            <button type="submit">Salvar Alterações</button>
        
            <a href="view_perfil.php"><p style="margin-left: 147px;">Cancelar e voltar</p></a>
        </form>
    </div>
</body>
</html>
