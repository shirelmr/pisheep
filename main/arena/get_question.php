<?php
header('Content-Type: application/json');
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'A';
$nivel = isset($_GET['nivel']) ? $_GET['nivel'] : 'N001';

$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

// Conexión
$conn = new mysqli($host, $usuario, $contrasena, $bd);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

// Obtener pregunta aleatoria del nivel y tipo
$sql_pregunta = "SELECT ID_pregunta_ABC, pregunta FROM Pregunta_ABC 
                 WHERE ID_nivel = '$nivel' AND Tipo = '$tipo' 
                 ORDER BY RAND() LIMIT 1";
$resultado_pregunta = $conn->query($sql_pregunta);

if ($resultado_pregunta->num_rows == 0) {
    http_response_code(404);
    echo json_encode(["error" => "No question found"]);
    exit();
}

$fila = $resultado_pregunta->fetch_assoc();
$id_pregunta = $fila["ID_pregunta_ABC"];
$pregunta_img = $fila["pregunta"];

// Obtener opciones
$sql_opciones = "SELECT opcion_texto, es_correcta FROM Opcion 
                 WHERE ID_pregunta_ABC = '$id_pregunta'";
$resultado_opciones = $conn->query($sql_opciones);

$opciones = [];
while ($op = $resultado_opciones->fetch_assoc()) {
    $opciones[] = [
        "img" => $op["opcion_texto"],
        "correct" => $op["es_correcta"] == 1
    ];
}

// Mezclar las opciones aleatoriamente
shuffle($opciones);

// Respuesta
echo json_encode([
    "question_id" => $id_pregunta,
    "image" => $pregunta_img,
    "options" => $opciones
]);

$conn->close();
?>