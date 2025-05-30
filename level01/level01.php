<?php
// Mostrar errores (desarrollo)
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

// Crear arreglo con niveles del 1 al 10
$niveles = [
    "N001", "N002", "N003", "N004", "N005",
    "N006", "N007", "N008", "N009", "N010"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa México</title>
    <link rel="stylesheet" href="level01.css">
</head>
<body>
    <header>
        <h1>π sheep</h1>
        <nav>
            <a href="../worldmap.php">home</a>
            <a href="arena/arena.html">arena</a>
            <a href="avatar/avatar.html">avatar</a> 
            <a href="../tienda/tienda.php">shop</a>
            <div class="user-icon"><img src="img_level01/user.svg" alt="User icon"></div>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button">Back</a>
            <img src="img_level01/map_mx.svg" alt="Mapa de México" class="map_mx">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

            <!-- Botones de niveles -->
            <?php
            $imagenes = [
                "N010" => ["carne norte", "carne_01.svg", 160],
                "N009" => ["desierto", "desierto_01.svg", null],
                "N008" => ["cactus", "cactus_01.svg", 180],
                "N007" => ["industria", "industria_01.svg", 220],
                "N006" => ["cascada", "cascada_01.svg", null],
                "N005" => ["tequila", "tequila_01.svg", 180],
                "N004" => ["mosaico", "mosaico_01.svg", null],
                "N003" => ["queso", "queso_01.svg", null],
                "N002" => ["maracas", "maracas_01.svg", null],
                "N001" => ["pyramid", "pyramid_01.svg", null]
            ];

            foreach ($niveles as $i => $nivel) {
                $desbloqueado = $i <= $progreso;
                $clase = $imagenes[$nivel][0];
                $src = $imagenes[$nivel][1];
                $width = $imagenes[$nivel][2];
                $estilo = $width ? "style='width: {$width}px;'" : "";

                if ($desbloqueado) {
                    echo "<a href='../preguntas/level.php?nivel=$nivel' class='img $clase'>";
                } else {
                    echo "<div class='img $clase bloqueado' title='Nivel bloqueado'>";
                }

                echo "<img src='img_level01/$src' alt='$clase' $estilo>";

                echo $desbloqueado ? "</a>" : "</div>";
            }
            ?>

            <!-- Currency -->
            <div class="currency">
                <img src="img_level01/coin.png" alt="Moneda">
                <span><?php echo $monedas; ?></span>
            </div>
        </div>
    </main>

    <style>
        /* Opcional: estilo para niveles bloqueados */
        .bloqueado img {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</body>
</html>

