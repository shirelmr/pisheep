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
$progreso = 0;
$stmt = $conn->prepare("SELECT monedas, progreso FROM Usuario WHERE ID_usuario = ?");
if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("s", $user_id); 
if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

$stmt->bind_result($monedas, $progreso);
if (!$stmt->fetch()) {
    die("No se encontró el usuario con ID: $user_id");
}
$stmt->close();

// Definir niveles UK (N061–N070)
$niveles = [
    "N061", "N062", "N063", "N064", "N065",
    "N066", "N067", "N068", "N069", "N070"
];

// Datos imagen, clase, ancho para cada nivel
$imagenes = [
    "N061" => ["bigben", "bigben.svg", 310],
    "N062" => ["bus", "bus.svg", 300],
    "N063" => ["chips", "chips.svg", 300],
    "N064" => ["money", "money.svg", 300],
    "N065" => ["scotish", "scotish.svg", 300],
    "N066" => ["sherlok", "sherlok.svg", 300],
    "N067" => ["soldier", "soldier.svg", 300],
    "N068" => ["tea", "tea.svg", 290],
    "N069" => ["telephone", "telephone.svg", 300],
    "N070" => ["trebol", "trebol.svg", 300]
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa UK</title>
    <link rel="stylesheet" href="uk.css">
    <style>
        .bloqueado img {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="../arena/arena.html">arena</a>
            <a href="../avatar/avatar.php">avatar</a> 
            <a href="../tienda/tienda.php">shop</a>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img/uk.svg" alt="Mapa de Reino Unido" class="map_mx">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

            <!-- Íconos con clases y estructura de México -->
            <?php
        foreach ($niveles as $i => $nivel) {
            // Lógica para desbloquear nivel: progreso debe ser >= 60 + índice
            $desbloqueado = ($i + 60) <= $progreso;

            $clase = $imagenes[$nivel][0];
            $src = $imagenes[$nivel][1];
            $width = $imagenes[$nivel][2];
            $estilo = $width ? "style='width: {$width}px;'" : "";

            if ($desbloqueado) {
                echo "<a href='../preguntas/level.php?nivel=$nivel' class='img $clase'>";
            } else {
                echo "<div class='img $clase bloqueado' title='Nivel bloqueado'>";
            }

            echo "<img src='img/$src' alt='$clase' $estilo>";

            echo $desbloqueado ? "</a>" : "</div>";
        }
        ?>
            <div class="currency">
                <img src="img/coin.png" alt="Moneda">
                <span><?php echo $monedas; ?></span>
            </div>
        
        </div>
    </main>
</body>
</html>
