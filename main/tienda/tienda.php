<?php
session_start();
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>

<!DOCTYPE html>
<html lang="es">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="tienda.css">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tienda</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header>
    <h1>π sheep</h1>
    <nav>
      <a href="../worldmap.html">home</a>
      <a href="../arena/arena.html">arena</a>
      <a href="avatar.html">avatar</a>
      <a href="shop.html">shop</a>
      <div class="user-icon"><img src="user.svg" alt="User icon"></div>
    </nav>
  </header>

  <div class="titulo">
    <h1>π Shop</h1>
  </div>
  <div class="avatar">
    <img src="sheep_base.svg" alt = "sheep">
  </div>
  <div class="store-container">
    
    <div class="item">
      <img src="../items/bag1.svg" alt="Bolsa YSL">
      <div class="item-details">
        <h2>Purse YSL</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/bag2.svg" alt="Bolsa LV">
      <div class="item-details">
        <h2>Purse LV</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/hat2.svg" alt="Sombrero 1">
      <div class="item-details">
        <h2>Asian Hat </h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/cup.svg" alt="Stanley">
      <div class="item-details">
        <h2>Stanley</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra">BUY</button>
      </div>
    </div>

  </div>

</body>
</html>
