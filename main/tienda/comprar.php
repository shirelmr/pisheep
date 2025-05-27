<?php
session_start();

header('Content-Type: application/json');

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

if (!isset($_POST['item_id']) || !isset($_POST['price'])) {
    echo json_encode(["success" => false, "message" => "Faltan parámetros"]);
    exit;
}

$item_id = $_POST['item_id'];
$price = $_POST['price'];
$user_id = $_SESSION['user_id'];

// Conexión a la base de datos
$conn = new mysqli("localhost", "TC2005B_601_1", "pAssWd_194742", "R_601_1");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión"]);
    exit;
}

// Empezar transacción
$conn->begin_transaction();

try {
    // Verificar si el usuario tiene suficientes monedas
    $stmt = $conn->prepare("SELECT monedas FROM Usuario WHERE ID_usuario = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($monedas);
    $stmt->fetch();
    $stmt->close();

    if ($monedas < $price) {
        // No hay suficientes monedas, rollback y error
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "No tienes suficientes monedas"]);
        exit;
    }

    // Descontar monedas
    $stmt = $conn->prepare("UPDATE Usuario SET monedas = monedas - ? WHERE ID_usuario = ?");
    $stmt->bind_param("is", $price, $user_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO Avatar (ID_usuario, ID_articulo) VALUES (?, ?)");
    $stmt->bind_param("si", $user_id, $item_id); // id_articulo es int, por eso "si" y no "ss"
    $stmt->execute();
    $stmt->close();

    // Confirmar transacción
    $conn->commit();

    echo json_encode(["success" => true, "message" => "¡Compra exitosa!"]);
} catch (Exception $e) {
    // Algo salió mal, revertir cambios
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Error en la compra: " . $e->getMessage()]);
}

$conn->close();
?>


