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

// Definir niveles de Francia (N031–N040)
$niveles = [
    "N031" => ["torre", "torre_04.svg", 200],
    "N032" => ["camiseta", "camiseta_04.svg", 180],
    "N033" => ["escargot", "escargot_04.svg", 150],
    "N034" => ["baguette", "baguette_04.svg", 170],
    "N035" => ["beret", "beret_04.svg", 160],
    "N036" => ["vino", "vino_04.svg", 190],
    "N037" => ["ratatouille", "ratatouille_04.svg", 175],
    "N038" => ["queso", "queso_04.svg", 160],
    "N039" => ["croissant", "croissant_04.svg", 180],
    "N040" => ["museo", "museo_04.svg", 150]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mapa Francia - Educación Matemáticas</title>
    <link rel="stylesheet" href="level04.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="math-arena.html">arena</a>
            <a href="avatar.html">avatar</a>
            <a href="shop.html">shop</a>
            <div class="user-icon"><img src="img_level04/user.svg" alt="User icon" /></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img_level04/map_francia.svg" alt="Mapa de Francia" class="map_francia" />

            <?php
            foreach ($niveles as $nivel => $info) {
                $clase = $info[0];
                $imagen = $info[1];
                $width = $info[2];

                $numNivel = (int)substr($nivel, 1);
                $desbloqueado = $numNivel <= $progreso;

                $estilo = $width > 0 ? "style='width: {$width}px;'" : "";

                if ($desbloqueado) {
                    echo "<a href='../preguntas/level.php?nivel=$nivel' class='img $clase'>";
                    echo "<img src='img_level04/$imagen' alt='$clase' $estilo>";
                    echo "</a>";
                } else {
                    echo "<div class='img $clase bloqueado' title='Nivel bloqueado'>";
                    echo "<img src='img_level04/$imagen' alt='$clase' $estilo>";
                    echo "</div>";
                }
            }
            ?>

            <!-- Mostrar monedas -->
            <div class="currency">
                <img src="img_level04/coin.png" alt="Moneda" />
                <span><?php echo $monedas; ?></span>
            </div>
        </div>
    </main>

    <style>
        .bloqueado img {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</body>
</html>
