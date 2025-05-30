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
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en prepare: " . $conn->error);
}

$stmt->bind_param("s", $user_id);

if (!$stmt->execute()) {
    die("Error en execute: " . $stmt->error);
}

$stmt->close();
$conn->close();

header("Location: success.html");
exit();
?>

