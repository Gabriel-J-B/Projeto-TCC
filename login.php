<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - EDUC Co-Laborare</title>
  <link rel="icon" type="image/png" href="C:\Users\tr3s4\Downloads\logo.jpg">
  <link rel="stylesheet" href="login&cadastrostyle.css">
</head>
<body>
  <div class="bg-image"></div>

  <div class="container blur-bg">
    <form action="processar_login.php" method="POST" class="login-form">
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
      </div>
      <button type="submit" class="btn">Entrar</button>
    </form>
    <p class="signup-link">Ainda não tem uma conta? <a href="cadastro.php">Crie uma agora!</a></p>
    <p class="signup-link"><a href="esqueceu_senha.php">Esqueceu sua senha?</a></p>
    <div class="logo">
      <img src="logo2.jpg" alt="Logo Adicional">
    </div>
  </div>
</body>
</html>
