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
    <title>Mapa México </title>
    <link rel="stylesheet" href="level01.css">
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="arena/arena.html">arena</a>
            <a href="avatar.html">avatar</a>
            <a href="shop.html">shop</a>
            <div class="user-icon"><img src="img_level01/user.svg" alt="User icon"></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img_level01/map_mx.svg" alt="Mapa de México" class="map_mx">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


            <!--botones -->
            <!-- <a href="orca.html" class="img orca"><img src="img_level01/orca_01.svg" alt="Orca" style="width: 160px;"></a> -->
            <a href="../preguntas/level.php?nivel=N010" class="img carne norte"><img src="img_level01/carne_01.svg" alt="Carne" style="width: 160px;"></a>
            <a href="../preguntas/level.php?nivel=N009" class="img desierto"><img src="img_level01/desierto_01.svg" alt="Desierto"></a>
            <a href="../preguntas/level.php?nivel=N008" class="img cactus"><img src="img_level01/cactus_01.svg" alt="Cactus" style="width: 180px;"></a>
            <a href="../preguntas/level.php?nivel=N007" class="img industria"><img src="img_level01/industria_01.svg" alt="Industria" style="width: 220px;"></a>
            <a href="../preguntas/level.php?nivel=N006" class="img cascada"><img src="img_level01/cascada_01.svg" alt="Cascada"></a>
            <a href="../preguntas/level.php?nivel=N005" class="img tequila"><img src="img_level01/tequila_01.svg" alt="Tequila" style="width: 180px;"></a>
            <a href="../preguntas/level.php?nivel=N004" class="img mosaico"><img src="img_level01/mosaico_01.svg" alt="Mosaico"></a>
            <a href="../preguntas/level.php?nivel=N003" class="img queso"><img src="img_level01/queso_01.svg" alt="Queso"></a>
            <a href="../preguntas/level.php?nivel=N002" class="img maracas"><img src="img_level01/maracas_01.svg" alt="Maracas"></a>
            <a href="../preguntas/level.php?nivel=N001" class="img pyramid"><img src="img_level01/pyramid_01.svg" alt="Piramide"></a>

            <!-- currency -->
            <div class="currency">
                <img src="img_level01/coin.png" alt="Moneda">
                <span><?php echo $monedas; ?></span>
            </div>
        </div>

        
    </main>
</body>
</html>
