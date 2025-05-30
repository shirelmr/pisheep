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
    <title>Mapa Brasil - Educación Matemáticas</title>
    <link rel="stylesheet" href="level03.css">
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="worldmap.php">home</a>
            <a href="math-arena.html">arena</a>
            <a href="avatar.html">avatar</a>
            <a href="shop.html">shop</a>
            <div class="user-icon"><img src="img_level03/user.svg" alt="User icon"></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img_level03/map_brasil.svg" alt="Mapa de Brasil" class="map_brasil">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


            <!-- Banderas como botones -->
            <a href="../preguntas/level.php?nivel=N023" class="img antifaz"><img src="img_level03/antifaz_03.svg" alt="Antifaz" style="width: 190px;"></a>
            <a href="../preguntas/level.php?nivel=N021" class="img ave"><img src="img_level03/ave_03.svg" alt="ave" style="width: 193px;"></a>
            <a href="../preguntas/level.php?nivel=N030" class="img balon"><img src="img_level03/balon_03.svg" alt="balon"></a>
            <a href="../preguntas/level.php?nivel=N026" class="img cristo"><img src="img_level03/cristo_03.svg" alt="cristo" style="width: 180px;"></a>
            <a href="../preguntas/level.php?nivel=N024" class="img guitarra"><img src="img_level03/guitarra_03.svg" alt="guitarra" style="width: 187px;"></a>
            <a href="../preguntas/level.php?nivel=N022" class="img maracas"><img src="img_level03/maracas_03.svg" alt="maracas"></a>
            <a href="../preguntas/level.php?nivel=N029" class="img pez"><img src="img_level03/pez_03.svg" alt="pez"></a>
            <a href="../preguntas/level.php?nivel=N025" class="img tambor"><img src="img_level03/tambor_03.svg" alt="tambor" style="width: 160px"></a>
            <a href="../preguntas/level.php?nivel=N028" class="img tapir"><img src="img_level03/tapir_03.svg" alt="tapir"></a>
            <a href="../preguntas/level.php?nivel=N027" class="img taza"><img src="img_level03/taza_03.svg" alt="taza"></a>

            <!-- currency -->
            <div class="currency">
                <img src="img_level03/coin.png" alt="Moneda">
                <span><?php echo $monedas; ?></span>
            </div>
        </div>

        
    </main>
</body>
</html>
