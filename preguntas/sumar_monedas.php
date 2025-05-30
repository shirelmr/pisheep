<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

$conn = new mysqli($host, $usuario, $contrasena, $bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Sumar 100 monedas
$sql = "UPDATE Usuario SET monedas = monedas + 100 WHERE ID_usuario = ?";
$stmt_monedas = $conn->prepare($sql);

if (!$stmt_monedas) {
    die("Error en prepare: " . $conn->error);
}

$stmt_monedas->bind_param("s", $user_id);

if (!$stmt_monedas->execute()) {
    die("Error en execute: " . $stmt_monedas->error);
}

$stmt_monedas->close();

// Obtener el nivel actual desde la URL
$id_nivel = isset($_GET['nivel']) ? $_GET['nivel'] : 'N001';
$nivel_actual = intval(substr($id_nivel, 1));

// Obtener el progreso actual del usuario
$sql_usuario = "SELECT progreso FROM Usuario WHERE ID_usuario = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
if (!$stmt_usuario) {
    die("Error en prepare (usuario): " . $conn->error);
}

$stmt_usuario->bind_param("s", $user_id);
$stmt_usuario->execute();
$stmt_usuario->bind_result($progreso_actual);

if ($stmt_usuario->fetch()) {
    $stmt_usuario->close();

    // Actualizar el progreso si el nivel actual es mayor
    if ($progreso_actual < $nivel_actual) {
        $sql_update = "UPDATE Usuario SET progreso = ? WHERE ID_usuario = ?";
        $stmt_update = $conn->prepare($sql_update);
        if (!$stmt_update) {
            die("Error en prepare (update): " . $conn->error);
        }

        $stmt_update->bind_param("is", $nivel_actual, $user_id);
        $stmt_update->execute();
        $stmt_update->close();
    }
} else {
    $stmt_usuario->close();
}

$conn->close();

header("Location: success.html");
exit();
?>


