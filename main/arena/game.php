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

    .ram {
      position: absolute;
      width: 20%;
    }

    .ram.left {
      left: 5%;
      top: 60%;
    }

    .ram.right {
      right: 5%;
      top: 60%;
    }

    .cloud {
      width: 110%;
      height: auto;
      position: absolute;
      top: -35%;
      left: -3%;
    }

    .answer_1 {
      position: absolute;
      top: 20%;
      left: 10%;
    }

    .answer_2 {
      position: absolute;
      top: 20%;
      right: 10%;
    }

    img {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="sheeps">
    <img src="ram-left.svg" alt="Ram left" class="ram left" />
    <img src="ram-rigth.svg" alt="Ram right" class="ram right" />
  </div>
  <div class="answer">
    <img src="circle.svg" alt="respuesta1" class="answer_1"/>
    <img src="circle.svg" alt="respuesta2" class="answer_2"/>
  </div>
  <div class= "cloud">
  <img src="cloud.svg" alt="nube" class="cloud"/>
  </div>


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