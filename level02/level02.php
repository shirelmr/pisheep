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
    "N011", "N012", "N013", "N014", "N015",
    "N016", "N017", "N018", "N019", "N020"
];

// Datos de imagen y clases
$imagenes = [
    "N011" => ["puente", "puente_02.svg", 160],
    "N012" => ["rock", "rock_02.svg", 150],
    "N013" => ["space", "space_02.svg", 200],
    "N014" => ["dolar", "dolar_02.svg", 150],
    "N015" => ["taxi", "taxi_02.svg", 150],
    "N016" => ["hotdog", "hotdog_02.svg", 130],
    "N017" => ["sombrero", "sombrero_02.svg", 150],
    "N018" => ["pizza", "pizza_02.svg", 150],
    "N019" => ["bean", "bean_02.svg", 140],
    "N020" => ["estatua", "estatua_02.svg", 255]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa USA - Educación Matemáticas</title>
    <link rel="stylesheet" href="level02.css">
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="math-arena.html">arena</a>
            <a href="avatar.html">avatar</a>
            <a href="shop.html">shop</a>
            <div class="user-icon"><img src="img_level02/user.svg" alt="User icon"></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img_level02/map_usa.svg" alt="Mapa de USA" class="map_usa">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

            <?php
            foreach ($niveles as $i => $nivel) {
                $desbloqueado = ($i + 10) <= $progreso; // niveles 11 a 20
                $clase = $imagenes[$nivel][0];
                $src = $imagenes[$nivel][1];
                $width = $imagenes[$nivel][2];
                $estilo = $width ? "style='width: {$width}px;'" : "";

                if ($desbloqueado) {
                    echo "<a href='../preguntas/level.php?nivel=$nivel' class='img $clase'>";
                } else {
                    echo "<div class='img $clase bloqueado' title='Nivel bloqueado'>";
                }

                echo "<img src='img_level02/$src' alt='$clase' $estilo>";

                echo $desbloqueado ? "</a>" : "</div>";
            }
            ?>

            <!-- currency -->
            <div class="currency">
                <img src="img_level02/coin.png" alt="Moneda">
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
