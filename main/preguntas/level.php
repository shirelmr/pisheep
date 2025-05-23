<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>level01</title>
  <style>
body {
    font-family: sans-serif;
    background: #b5d6d6;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 0;
    position: relative;
}
.map-container {
    position: relative;
    width: 100vw;
    height: 100vh;
    min-width: 1100px;
    background-color:#b5d6d6;
}

.op {
    position: absolute;
    width: 55px;
    transition: transform 0.2s ease-in-out;
}
.op img {
    width: 150%;
    border-radius: 5px;
}
.op:hover {
    transform: scale(1.1);
}
.que img {
    width: 60%;
    height: auto;
}
.que {
    position: absolute;
    top: 32%;  
    left: 28%;
    height: 10px;
    width: auto;
}

.op1 { top: 65%; left: 20%; }
.op2 { top: 65%; left: 38%; }
.op3 { top: 65%; left: 55%; }
.op4 { top: 65%; left: 73%; }

  </style>
</head>
<body>
  <img src="format.svg" class="map-container">
  
<?php
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'A';
include 'mostrar_pregunta.php';
?>

  
</body>
</html>

