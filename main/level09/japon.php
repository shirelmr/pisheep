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
    <title>Mapa Japon - Educación Matemáticas</title>
    <link rel="stylesheet" href="japon.css">
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="math-arena.html">arena</a>
            <a href="avatar.html">avatar</a>
            <a href="shop.html">shop</a>
            <div class="user-icon"><img src="user.svg" alt="User icon"></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="map_japon.svg" alt="Mapa de Japon" class="map_japon">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


            <!-- Banderas como botones -->
            <a href="../preguntas/level.php?nivel=N081" class="img abanico"><img src="abanico_09.svg" alt="Abanico"></a>
            <a href="../preguntas/level.php?nivel=N082" class="img arrozjap"><img src="arrozjap_09.svg" alt="ArrozJap"></a>
            <a href="../preguntas/level.php?nivel=N083" class="img conejo"><img src="conejo_09.svg" alt="Conejo"></a>
            <a href="../preguntas/level.php?nivel=N084" class="img gato"><img src="gato_09.svg" alt="Gato"></a>
            <a href="../preguntas/level.php?nivel=N085" class="img karate"><img src="karate_09.svg" alt="Karate"></a>
            <a href="../preguntas/level.php?nivel=N086" class="img mujer"><img src="mujer_09.svg" alt="Mujer"></a>
            <a href="../preguntas/level.php?nivel=N087" class="img peces"><img src="peces_09.svg" alt="Peces"></a>
            <a href="../preguntas/level.php?nivel=N088" class="img sushi"><img src="sushi_09.svg" alt="Sushi"></a>
            <a href="../preguntas/level.php?nivel=N089" class="img templojap"><img src="templojap_09.svg" alt="TemploJap"></a>
            <a href="../preguntas/level.php?nivel=N090" class="img torre"><img src="torre_09.svg" alt="Torre"></a>

            <!-- currency -->
            <div class="currency">
                <img src="coin.png" alt="Moneda">
                <span><?php echo $monedas; ?></span>
            </div>
        </div>

        
    </main>
</body>
</html>
