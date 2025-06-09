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
    "N091", "N092", "N093", "N094", "N095",
    "N096", "N097", "N098", "N099", "N0100"
];
$imagenes = [
    "N091" => ["botas", "botas_10.svg", 300],
    "N092" => ["castillo1", "castillo1_10.svg", 258],
    "N093" => ["castillo2", "castillo2_10.svg", 250],
    "N094" => ["gorro", "gorro_10.svg", 300],
    "N095" => ["matrioshca", "matrioshca_10.svg", 300],
    "N096" => ["oso", "oso_10.svg", 300],
    "N097" => ["pareja", "pareja_10.svg", 300],
    "N098" => ["taza", "taza_10.svg", 300],
    "N099" => ["tetera", "tetera_10.svg", 300],
    "N0100" => ["soldado", "soldado_10.svg", 300]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Rusia - Educación Matemáticas</title>
    <link rel="stylesheet" href="rusia.css">
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
            <img src="map_rusia.svg" alt="Mapa de Rusia" class="map_rusia">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


            <!-- Banderas como botones
            <a href="../preguntas/level.php?nivel=N091" class="img botas"><img src="botas_10.svg" alt="Botas"></a>
            <a href="../preguntas/level.php?nivel=N092" class="img castillo1"><img src="castillo1_10.svg" alt="Castillo1" style="width: 258px;"></a>
            <a href="../preguntas/level.php?nivel=N093" class="img castillo2"><img src="castillo2_10.svg" alt="Castillo2" style="width: 250px;"></a>
            <a href="../preguntas/level.php?nivel=N094" class="img gorro"><img src="gorro_10.svg" alt="Gorro"></a>
            <a href="../preguntas/level.php?nivel=N095" class="img matrioshca"><img src="matrioshca_10.svg" alt="Matrioshca"></a>
            <a href="../preguntas/level.php?nivel=N096" class="img oso"><img src="oso_10.svg" alt="Oso"></a>
            <a href="../preguntas/level.php?nivel=N097" class="img pareja"><img src="pareja_10.svg" alt="Pareja"></a>
            <a href="../preguntas/level.php?nivel=N098" class="img taza"><img src="taza_10.svg" alt="Taza"></a>
            <a href="../preguntas/level.php?nivel=N099" class="img tetera"><img src="tetera_10.svg" alt="Tetera"></a>
            <a href="../preguntas/level.php?nivel=N100" class="img soldado"><img src="soldado_10.svg" alt="Soldado"></a>

            currency -->
            <?php
    foreach ($niveles as $i => $nivel) {
        // Lógica para desbloquear nivel: progreso debe ser >= 60 + índice
        $desbloqueado = ($i + 90) <= $progreso;

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
