<?php
session_start();

// Define a variável $codigo apenas se o formulário ainda não foi enviado
if (!isset($_POST['email'])) {
    $_SESSION['codigoVerificacao'] = rand(100000, 999999);
    $codigo = $_SESSION['codigoVerificacao'];
} else {
    $codigo = ""; // Define $codigo como vazio para que o campo seja exibido
}

$email = $novaSenha = $confirmarSenha = "";
$emailErr = $novaSenhaErr = $confirmarSenhaErr = $codigoErr = "";
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpa o formulário
    $email = $novaSenha = $confirmarSenha = "";

    // Verificar se todos os campos necessários estão presentes
    if (isset($_POST['email'], $_POST['nova_senha'], $_POST['confirmar_senha'], $_POST['codigo'])) {
        // Obtenha os dados do formulário
        $email = $_POST['email'];
        $novaSenha = $_POST['nova_senha'];
        $confirmarSenha = $_POST['confirmar_senha'];
        $codigoInserido = $_POST['codigo'];

        // Estabeleça a conexão com o banco de dados
        $conn = new PDO("mysql:host=localhost;dbname=plataforma", "root", "admin");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar se o email existe no banco de dados
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            // Verificar se as senhas coincidem
            if ($novaSenha === $confirmarSenha) {
                // Verificar se o código inserido está correto
                if ($codigoInserido == $_SESSION['codigoVerificacao']) {
                    // O código está correto, atualize a senha no banco de dados
                    $hashedPassword = password_hash($novaSenha, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE usuarios SET senha = :novaSenha WHERE email = :email");
                    $stmt->bindParam(':novaSenha', $hashedPassword);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $mensagem = "Senha atualizada com sucesso!";
                    
                    echo "<script>
                            setTimeout(function() {
                                alert('$mensagem');
                                window.location.href = 'login.php';
                            }, 3000);
                          </script>";
                    exit();
                } else {
                    $codigoErr = "Código de verificação incorreto.";
                    echo "<script>
                            setTimeout(function() {
                              alert('$codigoErr');
                              window.location.href = 'esqueceu_senha.php';
                            }, 3000);
                          </script>";
                    exit();
                }
            } else {
                $confirmarSenhaErr = "As senhas não coincidem.";
                echo "<script>
                        setTimeout(function() {
                          alert('$confirmarSenhaErr');
                          window.location.href = 'esqueceu_senha.php';
                        }, 3000);
                      </script>";
                exit();
            }
        } else {
            $emailErr = "O email não está registrado.";
            echo "<script>
                    setTimeout(function() {
                      alert('$emailErr');
                      window.location.href = 'esqueceu_senha.php';
                    }, 3000);
                  </script>";
            exit();
        }
    } else {
        $emailErr = $novaSenhaErr = $confirmarSenhaErr = $codigoErr = "Todos os campos são obrigatórios.";
        echo "<script>
                setTimeout(function() {
                  alert('$emailErr\\n$novaSenhaErr\\n$confirmarSenhaErr\\n$codigoErr');
                  window.location.href = 'esqueceu_senha.php';
                }, 3000);
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Esqueceu a Senha - EDUC Co-Laborare</title>
  <link rel="icon" type="image/png" href="C:\Users\tr3s4\Downloads\logo.jpg">
  <link rel="stylesheet" href="login&cadastrostyle.css">
</head>
<body>
  <div class="bg-image"></div>

  <div class="container blur-bg">
    <?php if (!empty($mensagem)): ?>
    <div class="message"><?php echo $mensagem; ?></div>
    <?php endif; ?>
    <form id="form-esqueceu-senha" method="POST" class="login-form">
      <div class="form-group">
        <label for="email">Informe seu E-mail:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        <span class="error"><?php echo $emailErr; ?></span>
      </div>
      <div class="form-group">
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" id="nova_senha" name="nova_senha" required>
        <span class="error"><?php echo $novaSenhaErr; ?></span>
      </div>
      <div class="form-group">
        <label for="confirmar_senha">Confirmar Nova Senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" required>
        <span class="error"><?php echo $confirmarSenhaErr; ?></span>
      </div>
      <div class="form-group">
        <p>Código de Verificação: <?php echo $codigo; ?></p>
        <label for="codigo">Informe o Código de Verificação:</label>
        <input type="text" id="codigo" name="codigo" required>
      </div>
      <button type="submit" class="btn">Redefinir Senha</button>
    </form>
    <div class="logo">
      <img src="logo2.jpg" alt="Logo Adicional">
    </div>
    
    <a href="login.php"><p style="margin-left: 147px;">Voltar para login</p></a>
  </div>

  <script>
    // Limpar o formulário após a recarga da página
    window.onload = function() {
      document.getElementById("form-esqueceu-senha").reset();
    };
  </script>
</body>
</html>
