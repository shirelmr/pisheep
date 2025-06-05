<?php
// Mostrar errores (útil en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Datos de conexión a la base de datos
$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $bd);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// ... (código anterior)

$monedas = 0;
$stmt = $conn->prepare("SELECT monedas FROM Usuario WHERE ID_usuario = ?");
if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Cambia "s" por "i" si ID_usuario es numérico
$stmt->bind_param("s", $user_id); 
if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

$stmt->bind_result($monedas);

if (!$stmt->fetch()) {
    // Mostrar error detallado
    die("No se encontró el usuario con ID: $user_id o la columna 'monedas' no existe");
}

$stmt->close();

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
  <!--<link rel="stylesheet" href="styles.css" />-->
</head>
<body>
  <header>
    <h1>π sheep</h1>
    <nav>
      <a href="../worldmap.php">home</a>
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
  <img src="sheep_base.svg" alt="sheep" data-tipo="sheep">
<div class="monedas-container">
  <img src="../assets/img/coin.svg" alt="Moneda" class="moneda-icon">
  <span class="moneda-cantidad"><?php echo $monedas; ?></span>
</div>
<div id="preview-container"></div>
</div>


  
  <div class="store-container">
  


    <!--SOMBRERPS-->

    <div class="item">
      <img src="../items/items/hat1.svg" alt="Sombrero 1" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="1" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat2.svg" alt="Sombrero 2" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="2" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat3.svg" alt="Sombrero 3" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="3" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat4.svg" alt="Sombrero 4" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="4" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat5.svg" alt="Sombrero 5" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="5" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat6.svg" alt="Sombrero 6" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="6" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat7.svg" alt="Sombrero 7" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="7" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat8.svg" alt="Sombrero 8" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="8" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat9.svg" alt="Sombrero 9" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="9" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat10.svg" alt="Sombrero 10" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="10" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat11.svg" alt="Sombrero 11" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="11" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat12.svg" alt="Sombrero 12" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="12" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat13.svg" alt="Sombrero 13" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="13" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat14.svg" alt="Sombrero 14" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="14" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hat15.svg" alt="Sombrero 15" data-tipo="hat">
      <div class="item-details">
        <h2>Asian Hat</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="15" data-price="120">BUY</button>
      </div>
    </div>
<!--HANDES-->
    <div class="item">
      <img src="../items/items/hand1.svg" alt="Stanley" data-tipo="hand">
      <div class="item-details">
        <h2>Stanley</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="16" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hand2.svg" alt="Stanley" data-tipo="hand">
      <div class="item-details">
        <h2>Stanley</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="17" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hand3.svg" alt="Stanley" data-tipo="hand">
      <div class="item-details">
        <h2>Stanley</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="18" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hand4.svg" alt="Stanley" data-tipo="hand">
      <div class="item-details">
        <h2>Stanley</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="19" data-price="120">BUY</button>
      </div>
    </div>

    <div class="item">
      <img src="../items/items/hand5.svg" alt="Stanley" data-tipo="hand">
      <div class="item-details">
        <h2>Stanley</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="20" data-price="120">BUY</button>
      </div>
    </div>

    <!--BAG-->
    <div class="item">
      <img src="../items/bag1.svg" alt="Bolsa YSL" data-tipo="bag">
      <div class="item-details">
        <h2>Purse YSL</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="21" data-price="120">BUY</button>
      </div>
    </div>
  
    <div class="item">
      <img src="../items/items/bag2.svg" alt="Bolsa LV" data-tipo="bag">
      <div class="item-details">
        <h2>Purse LV</h2>
        <p class="price"><img src="../assets/img/coin.svg" alt="Moneda">120 coins</p> 
        <button class="boton_compra" data-id="22" data-price="120">BUY</button>
      </div>
    </div>
  

</body>
</html>

<script>
  document.querySelectorAll('.store-container .item img').forEach(img => {
    img.addEventListener('click', () => {
      const previewContainer = document.getElementById('preview-container');
      previewContainer.innerHTML = ''; // Limpia el preview anterior

      const previewImg = document.createElement('img');
      previewImg.src = img.src;

      // Quita clases viejas (por si acaso)
      previewImg.className = '';

      // Obtiene tipo del data attribute
      const tipo = img.getAttribute('data-tipo');
      if (tipo) {
        previewImg.classList.add(`preview-${tipo}`);
      }

      previewContainer.appendChild(previewImg);
    });
  });

  document.querySelectorAll('.boton_compra').forEach(button => {
  button.addEventListener('click', () => {
    const id = button.getAttribute('data-id');
    const price = button.getAttribute('data-price');

    fetch('comprar.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `item_id=${encodeURIComponent(id)}&price=${encodeURIComponent(price)}`
    })
    .then(response => response.json())
    .then(result => {
      alert(result.message);
      if (result.success) {
        location.reload();
      }
    })
    .catch(error => {
      console.error('Error al comprar:', error);
      alert("Error en la compra");
    });
  });
});

</script>