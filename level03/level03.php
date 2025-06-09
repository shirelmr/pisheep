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
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($monedas, $progreso);
if (!$stmt->fetch()) {
    die("No se encontró el usuario con ID: $user_id");
}
$stmt->close();

// Arreglo con niveles 11 al 20
$niveles = [
    "N021", "N022", "N023", "N024", "N025",
    "N026", "N027", "N028", "N029", "N030"
];

// Niveles de level03.php, por ejemplo del 21 al 30
$imagenes = [
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
            <a href="../arena/arena.php">arena</a>
            <a href="../avatar/avatar.php">avatar</a>
            <a href="../tienda/tienda.php">shop</a>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img_level03/map_brasil.svg" alt="Mapa de Brasil" class="map_brasil" />
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />

            <?php
            foreach ($niveles as $i => $nivel) {
                $desbloqueado = ($i + 20) <= $progreso; // niveles 11 a 20
                $clase = $imagenes[$nivel][0];
                $src = $imagenes[$nivel][1];
                $width = $imagenes[$nivel][2];
                $estilo = $width ? "style='width: {$width}px;'" : "";

                if ($desbloqueado) {
                    echo "<a href='../preguntas/level.php?nivel=$nivel' class='img $clase'>";
                } else {
                    echo "<div class='img $clase bloqueado' title='Nivel bloqueado'>";
                }

                echo "<img src='img_level03/$src' alt='$clase' $estilo>";

                echo $desbloqueado ? "</a>" : "</div>";
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

