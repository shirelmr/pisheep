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
    <title>Mapa Egipto</title>
    <link rel="stylesheet" href="egypt.css">
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="math-arena.html">arena</a>
            <a href="avatar.html">avatar</a>
            <a href="shop.html">shop</a>
            <div class="user-icon"><img src="img/user.svg" alt="User icon"></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img/map_egypt.svg" alt="Mapa de Egipto" class="map_mx">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

            <!-- Íconos con clases y estructura de México -->
<a href="../preguntas/level.php?nivel=N041" class="img camello"><img src="img/camello_05.svg" alt="camello" style="width: 220px;"></a>
<a href="../preguntas/level.php?nivel=N042" class="img esfinge"><img src="img/esfinge_05.svg" alt="esfinge" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N043" class="img jarron"><img src="img/jarron_05.svg" alt="jarron" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N044" class="img mujer"><img src="img/mujer_05.svg" alt="mujer" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N045" class="img pergamino"><img src="img/pergamino_05.svg" alt="pergamino" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N046" class="img piramides"><img src="img/piramides_05.svg" alt="piramides" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N047" class="img roca"><img src="img/roca_05.svg" alt="roca" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N048" class="img templo"><img src="img/templo_05.svg" alt="templo" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N049" class="img tut"><img src="img/tut_05.svg" alt="persona" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N050" class="img gato"><img src="img/gato_05.svg" alt="gato" style="width: 270px;"></a>

            <!-- Botón back -->
            <div class="currency">
                <img src="img/coin.png" alt="Moneda">
                <span><?php echo $monedas; ?></span>
            </div>
        
        </div>
    </main>
</body>
</html>
