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

// Niveles de level03.php, por ejemplo del 21 al 30
$niveles = [
    "N021" => ["ave", "ave_03.svg", 193],
    "N022" => ["maracas", "maracas_03.svg", 0],
    "N023" => ["antifaz", "antifaz_03.svg", 190],
    "N024" => ["guitarra", "guitarra_03.svg", 187],
    "N025" => ["tambor", "tambor_03.svg", 160],
    "N026" => ["cristo", "cristo_03.svg", 180],
    "N027" => ["taza", "taza_03.svg", 0],
    "N028" => ["tapir", "tapir_03.svg", 0],
    "N029" => ["pez", "pez_03.svg", 0],
    "N030" => ["balon", "balon_03.svg", 0]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mapa Brasil - Educación Matemáticas</title>
    <link rel="stylesheet" href="level03.css" />
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="math-arena.html">arena</a>
            <a href="avatar.html">avatar</a>
            <a href="shop.html">shop</a>
            <div class="user-icon"><img src="img_level03/user.svg" alt="User icon" /></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img_level03/map_brasil.svg" alt="Mapa de Brasil" class="map_brasil" />
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />

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
                    echo "<img src='img_level03/$imagen' alt='$clase' $estilo>";
                    echo "</a>";
                } else {
                    echo "<div class='img $clase bloqueado' title='Nivel bloqueado'>";
                    echo "<img src='img_level03/$imagen' alt='$clase' $estilo>";
                    echo "</div>";
                }
            }
            ?>

            <!-- currency -->
            <div class="currency">
                <img src="img_level03/coin.png" alt="Moneda" />
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

