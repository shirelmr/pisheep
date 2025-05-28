<?php
session_start();
$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

$conn = new mysqli($host, $usuario, $contrasena, $bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
include 'db_connection.php';

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'A';
$nivel = isset($_GET['nivel']) ? $_GET['nivel'] : 'N001';

// Obtener pregunta aleatoria
$sql_pregunta = "SELECT ID_pregunta_ABC, pregunta FROM Pregunta_ABC 
                 WHERE ID_nivel = '$id_nivel' AND Tipo = '$tipo_actual' 
                 ORDER BY RAND() LIMIT 1";
$res = $conn->query($sql_pregunta);

if ($res->num_rows > 0) {
    $preg = $res->fetch_assoc();
    $id_pregunta = $preg['ID_pregunta_ABC'];
    $ruta_img_pregunta = $preg['pregunta'];

    echo '<img src="' . $ruta_img_pregunta . '" class="cloud" alt="Pregunta">';

    // Obtener opciones
    $sql_opciones = "SELECT opcion_texto, es_correcta FROM Opcion 
                     WHERE ID_pregunta_ABC = '$id_pregunta' 
                     ORDER BY RAND() LIMIT 2";
    $res_opciones = $conn->query($sql_opciones);

    $op_classes = ['answer_1', 'answer_2'];
    $i = 0;

    while ($op = $res_opciones->fetch_assoc()) {
        $ruta_op = $op['opcion_texto'];
        $correcta = $op['es_correcta'];
        echo '<img src="' . $ruta_op . '" class="' . $op_classes[$i] . '" data-correcta="' . $correcta . '" />';
        $i++;
    }
} else {
    echo "<p style='color:white'>No se encontró ninguna pregunta</p>";
}
$conn->close();
?>