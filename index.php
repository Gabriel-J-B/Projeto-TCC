<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EDUC Co-Laborare</title>
  <link rel="icon" type="image/png" href="C:\Users\tr3s4\Downloads\logo.jpg">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color:#666; /* Cor de fundo da página */
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .container {
      max-width: 960px;
      margin: 0 auto;
      padding: 0 20px;
    }

    header {
      background-color: #f2f2f2;
      padding: 80px 0;
      text-align: center;
    }

    header h1 {
      font-size: 36px;
      margin-bottom: 20px;
    }

    header p {
      font-size: 18px;
      color: #666;
      margin-bottom: 40px;
    }

    .btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      font-size: 18px;
      border-radius: 5px;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    .slider-container {
      width: 80%;
      margin: 50px auto;
      position: relative;
    }

    .slick-slide img {
      width: 100%;
      height: auto;
      position: relative;
    }

    .slick-slide {
      position: relative;
    }

    .slick-prev, .slick-next {
      font-size: 24px;
      color: #fff;
      background-color: rgba(0, 0, 0, 0.5); /* Cor de fundo inicial dos botões de navegação */
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      line-height: 40px;
      text-align: center;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 1;
      cursor: pointer;
    }

    .slick-prev:hover, .slick-next:hover {
      background-color: #007bff; /* Cor de fundo ao passar o mouse sobre os botões de navegação */
    }

    .slick-prev {
      left: 20px;
    }

    .slick-next {
      right: 20px;
    }

    .slick-dots {
      position: absolute;
      bottom: 10px; /* Posição vertical das bolinhas de navegação */
      left: 0;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .slick-dots li {
      display: inline-block;
      margin: 0 5px;
    }

    .slick-dots li button {
      font-size: 0;
      width: 20px; /* Largura das bolinhas de navegação */
      height: 10px; /* Altura das bolinhas de navegação */
      border-radius: 5px; /* Deixa as bolinhas achatadas */
      background-color: rgba(0, 0, 0, 0.5); /* Cor inicial das bolinhas de navegação */
      border: none;
      cursor: pointer;
      position: relative;
      transition: transform 0.3s, width 0.5s; /* Adiciona uma transição suave para a escala e o preenchimento */
    }

    .slick-dots li.slick-active button {
      transform: scale(1.5); /* Aumenta a escala do ponto ativo */
    }

    .slick-dots li button::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 0;
      background-color: #007bff; /* Cor da progressão da barra de progresso */
      border-radius: 5px; /* Deixa a barra de progresso achatada */
      transition: width 5s ease; /* A transição para o preenchimento já está especificada aqui */
    }

    .slick-dots li.slick-active button::before {
      width: 100%; /* Barra de progresso preenchida quando o slide está ativo */
    }

    footer {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 20px 0;
      width: 100%;
      margin-top: auto; /* O footer permanecerá no final da página */
    }

    footer p {
      font-size: 14px;
    }

    .logo {
      text-align: center;
      margin-bottom: 20px;
    }
  
    .logo img {
      width: 200px; /* Ajuste conforme necessário */
      height: auto;
      border-radius: 50%;
      border: 10px solid #fff; /* Adiciona um efeito de borda branca */
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Adiciona uma sombra suave */
    }

    .banner-description {
      position: absolute;
      top: 10%;
      left: 0; /* Alterado para 0 para alinhar à esquerda */
      transform: translateY(-50%);
      text-align: left; /* Alterado para alinhar o texto à esquerda */
      color: white;
      z-index: 2;
      padding: 20px;
      background-color: rgba(0, 0, 0, 0.5);
    }
  </style>

</head>
<body>

  <header>
    <div class="container">
      <div class="logo">
        <img src="logo.jpg" alt="Logo EDUC Co-Laborare">
      </div>
      <h1>EDUC Co-Laborare</h1>
      <p>O lugar onde a educação se torna uma experiência colaborativa e envolvente.</p>
      <a href="login.php" class="btn">Começar</a>
    </div>
  </header>

  <div class="slider-container">
    <div class="slick-slider">
      <div>
        <div class="banner-description">
          <h2>Descrição do Documentos</h2><br>
          <p>O professor pode anexar documentos à plataforma, e os alunos podem</p>
            <p>acessá-los em tempo real, juntamente com o professor. Além disso,<p>
            <p>eles podem fazer comentários e destacar o texto.</p>
        </div>
        <img src="documentos.jpg" alt="Banner 1">
      </div>
      <div>
        <div class="banner-description">
          <h2>Descrição do Quiz</h2><br>
          <p>Com base no conteúdo presente no documento, um algoritmo será dese - </p>
          <p> nvolvido para criar um quiz desafiador, o qual será apresentado   - </p>
          <p> para que alunos e professores resolvam.</p>
        </div>
        <img src="quiz.jpg" alt="Banner 2">
      </div>
      <div>
        <div class="banner-description">
          <h2>Descrição do Mapa Mental</h2><br>
          <p>Mapa mentais de conteúdo produzido por professor e alunos.</p>
        </div>
        <img src="mapamental.jpg" alt="Banner 3">
      </div>
    </div>
  </div>

  <footer>
      <p>&copy; 2024 EDUC Co-Laborare. Todos os direitos reservados. Desenvolvido por: Gabriel Jacobsen Berchtold.</p>
      <p>Menção honrosa ao プロジェクト カ (Projekt K) pela colaboração e apoio ao longo do desenvolvimento de projetos anteriores.</p>
  </footer>
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
  <script>
    $(document).ready(function(){
      const slider = $('.slick-slider');
  
      slider.slick({
        autoplay: true,
        autoplaySpeed: 5000,
        dots: true,
        arrows: true,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="slick-prev">&#10094;</button>',
        nextArrow: '<button type="button" class="slick-next">&#10095;</button>',
      });
  
      const progressBar = $('.progress-bar');
      let timer;
  
      function startProgressBar() {
        progressBar.css('width', '0');
        progressBar.animate({ width: '100%' }, 5000, 'linear');
        timer = setTimeout(() => {
          slider.slick('slickNext');
          startProgressBar();
        }, 5000);
      }

      startProgressBar();

      $('.pause-btn').on('click', function() {
        if ($(this).text() === 'Pausar') {
          clearTimeout(timer);
          progressBar.stop();
          $(this).text('Continuar');
        } else {
          startProgressBar();
          $(this).text('Pausar');
        }
      });
    });
  </script>

</body>
</html>
