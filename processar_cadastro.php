<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conecta ao banco de dados
    $host = "localhost";
    $username = "root";
    $password = "admin";
    $database = "plataforma";

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha
    
    // Mapeia o valor da tag para "Aluno" ou "Professor"
    $tag = isset($_POST['tag']) ? ($_POST['tag'] == "tag1" ? "Aluno" : "Professor") : "";

    // Mapeia o valor do estado para o nome real
    $estado_options = array(
        "estado1" => "Rio Grande do Sul",
        "estado2" => "Outro Estado"
    );
    $estado = isset($_POST['estado']) ? $estado_options[$_POST['estado']] : "";

    // Mapeia o valor da cidade para o nome real
    $cidade_options = array(
        "cidade1" => "Guaíba",
        "cidade2" => "Porto Alegre"
    );
    $cidade = isset($_POST['cidade']) ? $cidade_options[$_POST['cidade']] : "";

    $escola = isset($_POST['escola']) ? $_POST['escola'] : ""; // Verifica se a escola foi enviada

    // Debug: Verifica os valores capturados
    echo "Nome: $nome<br>";
    echo "E-mail: $email<br>";
    echo "Tag: $tag<br>";
    echo "Estado: $estado<br>";
    echo "Cidade: $cidade<br>";
    echo "Escola: $escola<br>";

    // Prepara a consulta SQL para inserir os dados na tabela
    $sql = "INSERT INTO usuarios (nome, email, senha, tag, estado, cidade, escola) 
            VALUES ('$nome', '$email', '$senha', '$tag', '$estado', '$cidade', '$escola')";

    // Executar consulta
    if ($conn->query($sql) === TRUE) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
}
?>