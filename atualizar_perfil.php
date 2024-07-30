<?php
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

// Obtém o ID do usuário da sessão
$usuario_id = $_SESSION['user_id'];

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $escola = $_POST['escola'];

    // Verifica se foi enviado um arquivo de imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['name']) {
        // Define o diretório de upload
        $destino = 'uploads/';
        // Obtém o nome do arquivo de imagem e gera um nome único
        $imagem_nome = uniqid('imagem_') . '_' . basename($_FILES["imagem"]["name"]);
        $imagem_caminho = $destino . $imagem_nome;
        
        // Move o arquivo de imagem para o diretório de upload
        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagem_caminho)) {
            // Atualiza os dados no banco de dados, incluindo o caminho da imagem
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, estado = :estado, cidade = :cidade, escola = :escola, imagem = :imagem";
            $params = array(':nome' => $nome, ':email' => $email, ':senha' => $senha, ':estado' => $estado, ':cidade' => $cidade, ':escola' => $escola, ':imagem' => $imagem_caminho);
        } else {
            echo "Erro ao fazer o upload da imagem.";
            exit();
        }
    } else {
        // Se não foi enviado um novo arquivo de imagem, atualiza apenas os outros campos
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, estado = :estado, cidade = :cidade, escola = :escola";
        $params = array(':nome' => $nome, ':email' => $email, ':senha' => $senha, ':estado' => $estado, ':cidade' => $cidade, ':escola' => $escola);
    }

    $sql .= " WHERE id = :id";
    $params[':id'] = $usuario_id;

    // Executa a consulta
    $stmt = $conn->prepare($sql);
    if ($stmt->execute($params)) {
        echo "Perfil atualizado com sucesso!";
        // Redireciona para a homepage após 3 segundos
        header("refresh:3; url=homepage.php");
        exit();
    } else {
        echo "Erro ao atualizar o perfil: " . $stmt->errorInfo()[2];
        // Redireciona para a página de perfil após 3 segundos
        header("refresh:3; url=perfil.php");
        exit();
    }
}

// Fechar a conexão com o banco de dados
$conn = null;
?>
