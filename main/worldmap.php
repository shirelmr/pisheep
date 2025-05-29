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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Interactivo - Educación Matemáticas</title>
    <link rel="stylesheet" href="worldmap.css">
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <!-- <a href="home.html">home</a> -->
            <a href="index.html">home</a>
            <a href="arena/arena.html">arena</a>
            <a href="avatar/avatar.php">avatar</a>
            <a href="tienda/tienda.php">shop</a>
            <div class="user-icon"><img src="imgWEB/user.svg" alt="User icon"></div>

        </nav>
    </header>

    <main>
        <div class="map-container">
            <img src="imgWEB/worldmap.jpg" alt="Mapa del mundo" class="map">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


            <!-- Banderas como botones -->
            <a href="level01/level01.php" class="flag mexico"><img src="imgWEB/flags/mexico.png" alt="México" style="width: 66px;"></a>
            <a href="level02/level02.php" class="flag usa"><img src="imgWEB/flags/usa.png" alt="USA" style="width: 75px;"></a>
            <a href="level07/uk.php" class="flag uk"><img src="imgWEB/flags/uk.png" alt="UK" style="width: 62px;"></a>
            <a href="level04/francia.php" class="flag france"><img src="imgWEB/flags/france.png" alt="Francia" style="width: 60px;"></a>
            <a href="level03/level03.php" class="flag brazil"><img src="imgWEB/flags/brazil.png" alt="Brasil" style="width: 66px;"></a>
            <a href="level05/egypt.php" class="flag egypt"><img src="imgWEB/flags/egypt.png" alt="Egipto" style="width: 60px;"></a>
            <a href="level06/arabia.php" class="flag saudi"><img src="imgWEB/flags/saudi.png" alt="Arabia Saudita" style="width: 65px;"></a>
            <!-- <a href="level08/level08.html" class="flag india"><img src="imgWEB/flags/india.png" alt="India"></a> -->
            <a href="level08/china.php" class="flag china"><img src="imgWEB/flags/china.png" alt="China" style="width: 65px;"></a>
            <a href="level09/japon.php" class="flag japan"><img src="imgWEB/flags/japan.png" alt="Japón" style="width: 65px;"></a>
            <a href="level10/rusia.php" class="flag russia"><img src="imgWEB/flags/russia.png" alt="Rusia" style="width: 65px;"></a>
            <!-- <a href="australia.html" class="flag australia"><img src="imgWEB/flags/australia.png" alt="Australia"></a> -->

            <!-- currency -->
            <div class="currency">
                <img src="imgWEB/coin.png" alt="Moneda">
                <span><?php echo $monedas; ?></span>
            </div>
        </div>

        
    </main>
</body>
</html>
