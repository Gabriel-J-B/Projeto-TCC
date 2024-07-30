<?php
// Informações de conexão com o banco de dados
$host = "localhost"; // Endereço do servidor do banco de dados
$username = "root"; // Nome de usuário do banco de dados
$password = "admin"; // Senha do banco de dados
$database = "plataforma"; // Nome do banco de dados

try {
    // Conectando ao banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    
    // Definindo o modo de erro do PDO como exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Exibindo mensagem de conexão bem-sucedida
    echo "Conexão bem-sucedida!";
} catch(PDOException $e) {
    // Caso ocorra um erro na conexão, exibe a mensagem de erro
    echo "Falha na conexão: " . $e->getMessage();
}
?>
