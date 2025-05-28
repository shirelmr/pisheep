<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Game Arena</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-image: url("fondo.png");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
    }
    .map-container {
    position: relative;
    width: 100vw;
    height: 100vh;
    min-width: 1100px;
    background-color:#b5d6d6;
}
  </style>
</head>
<body>
  <img src = "../arena/arena_bg.svg" class= "map-container">


  <?php
  $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'A';
  include 'get_question.php';
  ?>

  <script>
    document.querySelectorAll('img[data-correcta]').forEach(img => {
      img.addEventListener('click', () => {
        const correct = img.getAttribute('data-correcta') === "1";
        img.style.border = correct ? "4px solid green" : "4px solid red";

        setTimeout(() => {
          if (correct) {
            window.location.href = "game.php?tipo=<?php echo ($tipo === 'A') ? 'B' : 'fin'; ?>";
          } else {
            img.style.border = "none";
          }
        }, 800);
      });
    });
  </script>
</body>
</html>