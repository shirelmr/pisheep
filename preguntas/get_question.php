<?php
// FIXED get_question.php
session_start();
$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

$conn = new mysqli($host, $usuario, $contrasena, $bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'A';
$nivel = isset($_GET['nivel']) ? $_GET['nivel'] : 'N001';

echo "<!-- Debug: tipo=$tipo, nivel=$nivel -->"; // Debug info

// Get random question
$sql_pregunta = "SELECT ID_pregunta_ABC, pregunta FROM Pregunta_ABC 
                 WHERE ID_nivel = '$nivel' AND Tipo = '$tipo' 
                 ORDER BY RAND() LIMIT 1";

echo "<!-- Debug SQL: $sql_pregunta -->"; // Debug info

$res = $conn->query($sql_pregunta);

if ($res && $res->num_rows > 0) {
    $preg = $res->fetch_assoc();
    $id_pregunta = $preg['ID_pregunta_ABC'];
    $ruta_img_pregunta = $preg['pregunta'];

    echo '<img src="' . $ruta_img_pregunta . '" class="cloud" alt="Pregunta">';
    echo "<!-- Debug: Found question ID: $id_pregunta -->"; // Debug info

    // Get exactly 2 random options
    $sql_opciones = "SELECT opcion_texto, es_correcta FROM Opcion 
                     WHERE ID_pregunta_ABC = '$id_pregunta' 
                     ORDER BY RAND() LIMIT 2";
    
    echo "<!-- Debug SQL options: $sql_opciones -->"; // Debug info
    
    $res_opciones = $conn->query($sql_opciones);

    if ($res_opciones && $res_opciones->num_rows > 0) {
        $op_classes = ['answer_1', 'answer_2'];
        $i = 0;

        while ($op = $res_opciones->fetch_assoc() and $i < 2) {
            $ruta_op = $op['opcion_texto'];
            $correcta = $op['es_correcta'];
            
            echo '<img src="' . $ruta_op . '" class="' . $op_classes[$i] . '" data-correcta="' . $correcta . '" onclick="handleAnswer(this)" alt="Option ' . ($i+1) . '" />';
            echo "<!-- Debug: Option $i - Correct: $correcta -->"; // Debug info
            
            $i++;
        }
    } else {
        echo "<p style='color:red; position:absolute; top:10px; left:10px;'>No se encontraron opciones para la pregunta ID: $id_pregunta</p>";
    }
} else {
    echo "<p style='color:red; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);'>No se encontró ninguna pregunta para Tipo: $tipo, Nivel: $nivel</p>";
}

$conn->close();
?>