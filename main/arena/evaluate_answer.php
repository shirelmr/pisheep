<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$question_id = $data['question_id'] ?? null;
$selected_img = $data['selected_img'] ?? null;

if (!$question_id || !$selected_img) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing data']);
    exit();
}

// DB connection
$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

$conn = new mysqli($host, $usuario, $contrasena, $bd);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed']);
    exit();
}

$sql = "SELECT es_correcta FROM Opcion 
        WHERE ID_pregunta_ABC = ? AND opcion_texto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $question_id, $selected_img);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['correct' => $row['es_correcta'] == 1]);
} else {
    echo json_encode(['error' => 'Option not found']);
}

$stmt->close();
$conn->close();
?>