<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - EDUC Co-Laborare</title>
  <link rel="icon" type="image/png" href="C:\Users\tr3s4\Downloads\logo.jpg">
  <link rel="stylesheet" href="login&cadastrostyle.css">
</head>
<body>
  <div class="bg-image"></div>
  
  <div class="container blur-bg">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="signup-form">
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
      </div>
      <div class="form-group">
        <label for="tag">Tag:</label>
        <select id="tag" name="tag">
          <option value="tag1">Aluno</option>
          <option value="tag2">Professor</option>
        </select>
      </div>
      <div class="form-group">
        <label for="estado">Estado:</label>
        <select id="estado" name="estado">
          <option value="estado1">Rio Grande do Sul</option>
          <option value="estado2">NA</option>
        </select>
      </div>
      <div class="form-group">
        <label for="cidade">Cidade:</label>
        <select id="cidade" name="cidade">
          <option value="cidade1">Guaíba</option>
          <option value="cidade2">Porto Alegre</option>
        </select>
      </div>
      <div class="form-group">
        <label for="escola">Escola:</label>
        <input type="text" id="escola" name="escola">
      </div>
      <button type="submit" class="btn">Cadastrar</button>
      
      <?php include 'processar_cadastro.php'; ?>
    
    </form>
    <p class="login-link">Já tem uma conta? <a href="login.php">Faça login!</a></p>
    <div class="logo">
      <img src="logo2.jpg" alt="Logo Adicional">
    </div>
  </div>
</body>
</html>