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

$niveles = [
    "N031", "N032", "N033", "N034", "N035",
    "N036", "N037", "N038", "N039", "N040"
];

$imagenes = [
    "N031" => ["vino", "wine.svg", 300],
    "N032" => ["arco", "arc.svg", 300],
    "N033" => ["torre", "eiffel.svg", 300],
    "N034" => ["iglesia", "notredame.svg", 300],
    "N035" => ["croissant", "crosaint.svg", 300],
    "N036" => ["bolsa", "louis.svg", 300],
    "N037" => ["perfume", "dior.svg", 300],
    "N038" => ["scooter", "scooter.svg", 300],
    "N039" => ["pan", "baguette.svg", 300],
    "N040" => ["cupcake", "cupcake.svg", 300]
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mapa France</title>
    <link rel="stylesheet" href="francia.css">
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
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img/france.svg" alt="Mapa de Francia" class="map_francia">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

            <!-- botones img-->
            <!--<a href="../preguntas/level.php?nivel=N031" class="img vino"><img src="img/wine.svg" alt="Vino" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N032" class="img arco"><img src="img/arc.svg" alt="Arco del Triunfo" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N033" class="img torre"><img src="img/eiffel.svg" alt="Torre Eiffel" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N034" class="img iglesia"><img src="img/notredame.svg" alt="Notre Dame" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N035" class="img croissant"><img src="img/crosaint.svg" alt="Croissant" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N036" class="img bolsa"><img src="img/louis.svg" alt="Bolsa Louis Vuitton" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N037" class="img perfume"><img src="img/dior.svg" alt="Perfume" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N038" class="img scooter"><img src="img/scooter.svg" alt="Scooter" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N039" class="img pan"><img src="img/baguette.svg" alt="Baguette" style="width: 300px;"></a>
            <a href="../preguntas/level.php?nivel=N040" class="img cupcake"><img src="img/cupcake.svg" alt="Cupcake" style="width: 300px;"></a>-->
            <?php
            foreach ($niveles as $i => $nivel) {
                $desbloqueado = ($i + 30) <= $progreso; 
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
    <style>
        .bloqueado img {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</body>
</html>