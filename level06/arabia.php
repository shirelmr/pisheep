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
// Definir niveles de Egipto (N041–N050)
$niveles = [
    "N051", "N052", "N053", "N054", "N055",
    "N056", "N057", "N058", "N059", "N060"
];

$imagenes = [
    "N051" => ["camellos", "camellos_06.svg", 255],
    "N052" => ["casa", "casa_06.svg", 240],
    "N053" => ["chocolatera", "chocolatera_06.svg", 230],
    "N054" => ["flower", "flower_06.svg", 255],
    "N055" => ["food", "food_06.svg", 208],
    "N056" => ["kaaba", "kaaba_06.svg", 200],
    "N057" => ["mosaico", "mosaico_06.svg", 200],
    "N058" => ["paloma", "paloma_06.svg", 200],
    "N059" => ["persona", "persona_06.svg", 230],
    "N060" => ["smooking", "smooking_06.svg", 220]
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Arabia Saudita</title>
    <link rel="stylesheet" href="arabia.css">
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
            <a href="../worldmap.php" class="return-button"> Back </a>
            <img src="img/map_saudia.svg" alt="Mapa de Arabia Saudita" class="map_mx">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

            <!-- Íconos con clases y estructura de México -->
<!--<a href="../preguntas/level.php?nivel=N051" class="img camellos"><img src="img/camellos_06.svg" alt="camellos" style="width: 255px;"></a>
<a href="../preguntas/level.php?nivel=N052" class="img casa"><img src="img/casa_06.svg" alt="casa" style="width: 240px;"></a>
<a href="../preguntas/level.php?nivel=N053" class="img chocolatera"><img src="img/chocolatera_06.svg" alt="chocolatera" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N054" class="img flower"><img src="img/flower_06.svg" alt="flower" style="width: 255px;"></a>
<a href="../preguntas/level.php?nivel=N055" class="img food"><img src="img/food_06.svg" alt="pergamino" style="width: 208px;"></a>
<a href="../preguntas/level.php?nivel=N056" class="img kaaba"><img src="img/kaaba_06.svg" alt="kaaba" style="width: 200px;"></a>
<a href="../preguntas/level.php?nivel=N057" class="img mosaico"><img src="img/mosaico_06.svg" alt="mosaico" style="width: 200px;"></a>
<a href="../preguntas/level.php?nivel=N058" class="img paloma"><img src="img/paloma_06.svg" alt="paloma" style="width: 200px;"></a>
<a href="../preguntas/level.php?nivel=N059" class="img persona"><img src="img/persona_06.svg" alt="tut" style="width: 230px;"></a>
<a href="../preguntas/level.php?nivel=N060" class="img smooking"><img src="img/smooking_06.svg" alt="smooking" style="width: 220px;"></a>-->

            <!-- Botón back -->
            <?php
            foreach ($niveles as $i => $nivel) {
                $desbloqueado = ($i + 50) <= $progreso; 
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
</body>
</html>
