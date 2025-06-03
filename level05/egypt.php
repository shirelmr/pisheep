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

$user_id = $_SESSION['user_id'];

// Conexión a la base de datos
$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

$conn = new mysqli($host, $usuario, $contrasena, $bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener monedas y progreso
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
// Definir niveles de Egipto (N041–N050)
$niveles = [
    "N041" => ["camello", "camello_05.svg", 220],
    "N042" => ["esfinge", "esfinge_05.svg", 230],
    "N043" => ["jarron", "jarron_05.svg", 230],
    "N044" => ["mujer", "mujer_05.svg", 230],
    "N045" => ["pergamino", "pergamino_05.svg", 230],
    "N046" => ["piramides", "piramides_05.svg", 230],
    "N047" => ["roca", "roca_05.svg", 230],
    "N048" => ["templo", "templo_05.svg", 230],
    "N049" => ["tut", "tut_05.svg", 230],
    "N050" => ["gato", "gato_05.svg", 270]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Egipto</title>
    <link rel="stylesheet" href="egypt.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
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
            <a href="math-arena.html">arena</a>
            <a href="avatar.html">avatar</a>
            <a href="shop.html">shop</a>
            <div class="user-icon"><img src="img/user.svg" alt="User icon"></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button">Back</a>
            <img src="img/map_egypt.svg" alt="Mapa de Egipto" class="map_mx">

            <?php
            // Mostrar niveles con desbloqueo según progreso
            foreach ($niveles as $nivel => $info) {
                $clase = $info[0];
                $imagen = $info[1];
                $width = $info[2];

                // La lógica para desbloquear: el nivel es numérico, ej: N021 -> 21
                $numNivel = (int)substr($nivel, 1);

                $desbloqueado = $numNivel <= $progreso;

                $estilo = $width > 0 ? "style='width: {$width}px;'" : "";


                if ($desbloqueado) {
                    echo "<a href='../preguntas/level.php?nivel=$nivel' class='img $clase'>";
                    echo "<img src='img/$imagen' alt='$clase' style='width: {$width}px;'>";
                    echo "</a>";
                } else {
                    echo "<div class='img $clase bloqueado' title='Nivel bloqueado'>";
                    echo "<img src='img/$imagen' alt='$clase' style='width: {$width}px;'>";
                    echo "</div>";
                }
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
