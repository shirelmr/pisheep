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
    "N071", "N072", "N073", "N074", "N075",
    "N076", "N077", "N078", "N079", "N080"
];

// Datos imagen, clase, ancho para cada nivel
$imagenes = [
    "N071" => ["arbol", "arbol_08.svg", 300],
    "N072" => ["arroz", "arroz_08.svg", 300],
    "N073" => ["dragon", "dragon_08.svg", 210],
    "N074" => ["flor", "flor_08.svg", 220],
    "N075" => ["lampara", "lampara_08.svg", 220],
    "N076" => ["muralla", "muralla_08.svg", 300],
    "N077" => ["panda", "panda_08.svg", 300],
    "N078" => ["platon", "platon_08.svg", 300],
    "N079" => ["sensei", "sensei_08.svg", 300],
    "N080" => ["templo", "templo_08.svg", 300]
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa China - Educación Matemáticas</title>
    <link rel="stylesheet" href="china.css">
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
            <img src="map_china.svg" alt="Mapa de China" class="map_china">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


            <!-- Banderas como botones 
            <a href="../preguntas/level.php?nivel=N071" class="img arbol"><img src="arbol_08.svg" alt="Arbol" ></a>
            <a href="../preguntas/level.php?nivel=N072" class="img arroz"><img src="arroz_08.svg" alt="Arroz"></a>
            <a href="../preguntas/level.php?nivel=N073" class="img dragon"><img src="dragon_08.svg" alt="Dragon" style="width: 210px;"></a>
            <a href="../preguntas/level.php?nivel=N074" class="img flor"><img src="flor_08.svg" alt="Flor" style="width: 220px;"></a>
            <a href="../preguntas/level.php?nivel=N075" class="img lampara"><img src="lampara_08.svg" alt="Lampara" style="width: 220px;"></a>
            <a href="../preguntas/level.php?nivel=N076" class="img muralla"><img src="muralla_08.svg" alt="Muralla"></a>
            <a href="../preguntas/level.php?nivel=N077" class="img panda"><img src="panda_08.svg" alt="Panda"></a>
            <a href="../preguntas/level.php?nivel=N078" class="img platon"><img src="platon_08.svg" alt="Platon"></a>
            <a href="../preguntas/level.php?nivel=N079" class="img sensei"><img src="sensei_08.svg" alt="Sensei"></a>
            <a href="../preguntas/level.php?nivel=N080" class="img templo"><img src="templo_08.svg" alt="Templo"></a>

           currency -->
            <?php
    foreach ($niveles as $i => $nivel) {
        // Lógica para desbloquear nivel: progreso debe ser >= 60 + índice
        $desbloqueado = ($i + 70) <= $progreso;

        $clase = $imagenes[$nivel][0];
        $src = $imagenes[$nivel][1];
        $width = $imagenes[$nivel][2];
        $estilo = $width ? "style='width: {$width}px;'" : "";

        if ($desbloqueado) {
            echo "<a href='../preguntas/level.php?nivel=$nivel' class='img $clase'>";
        } else {
            echo "<div class='img $clase bloqueado' title='Nivel bloqueado'>";
        }

        echo "<img src='$src' alt='$clase' $estilo>";

        echo $desbloqueado ? "</a>" : "</div>";
    }
    ?>
            <div class="currency">
                <img src="coin.png" alt="Moneda">
                <span><?php echo $monedas; ?></span>
            </div>
        </div>

        
    </main>
</body>
</html>
