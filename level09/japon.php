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
    "N081", "N082", "N083", "N084", "N085",
    "N086", "N087", "N088", "N089", "N090"
];
$imagenes = [
    "N081" => ["abanico", "abanico_09.svg", 300],
    "N082" => ["arrozjap", "arrozjap_09.svg", 300],
    "N083" => ["conejo", "conejo_09.svg", 300],
    "N084" => ["gato", "gato_09.svg", 300],
    "N085" => ["karate", "karate_09.svg", 300],
    "N086" => ["mujer", "mujer_09.svg", 300],
    "N087" => ["peces", "peces_09.svg", 300],
    "N088" => ["sushi", "sushi_09.svg", 300],
    "N089" => ["templojap", "templojap_09.svg", 300],
    "N090" => ["torre", "torre_09.svg", 300]
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Japon - Educación Matemáticas</title>
    <link rel="stylesheet" href="japon.css">
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
            <a href="../arena/arena.php">arena</a>
            <a href="../avatar/avatar.php">avatar</a>
            <a href="../tienda/tienda.php">shop</a>
        </nav>
    </header>

    <main>
        <div class="map-container">
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="map_japon.svg" alt="Mapa de Japon" class="map_japon">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


            <!-- Banderas como botones 
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

             currency -->

            <?php
    foreach ($niveles as $i => $nivel) {
        // Lógica para desbloquear nivel: progreso debe ser >= 60 + índice
        $desbloqueado = ($i + 80) <= $progreso;

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
