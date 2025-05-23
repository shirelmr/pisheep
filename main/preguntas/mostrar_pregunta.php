<?php
session_start();

// Conexión usando las credenciales reales de tu base de datos
$host = "localhost";
$usuario = "TC2005B_601_1";
$contrasena = "pAssWd_194742";
$bd = "R_601_1";

$conn = new mysqli($host, $usuario, $contrasena, $bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Variables del flujo: primero A luego B
$tipo_actual = isset($_GET['tipo']) ? $_GET['tipo'] : 'A';
$id_nivel = isset($_GET['nivel']) ? $_GET['nivel'] : 'N001';

// Obtener la pregunta del tipo actual (A o B)
$sql_pregunta = "SELECT ID_pregunta_ABC, pregunta FROM Pregunta_ABC 
                 WHERE ID_nivel = '$id_nivel' AND Tipo = '$tipo_actual' 
                 ORDER BY RAND() LIMIT 1";
$resultado_pregunta = $conn->query($sql_pregunta);

if ($resultado_pregunta->num_rows > 0) {
    $fila = $resultado_pregunta->fetch_assoc();
    $id_pregunta = $fila["ID_pregunta_ABC"];
    $rutaImagen = $fila["pregunta"];

    echo '<div class="que"><img src="' . $rutaImagen . '" alt="pregunta"></div>';

    // Mostrar opciones
    $sql_opciones = "SELECT opcion_texto, es_correcta FROM Opcion WHERE ID_pregunta_ABC = '$id_pregunta'";
    $resultado_opciones = $conn->query($sql_opciones);

    $clases_opciones = ["op1", "op2", "op3", "op4"];
    $indice = 0;

    if ($resultado_opciones->num_rows > 0) {
        while ($fila = $resultado_opciones->fetch_assoc()) {
            if ($indice < count($clases_opciones)) {
                $rutaOpcion = $fila["opcion_texto"];
                $esCorrecta = $fila["es_correcta"];
                $clase = $clases_opciones[$indice];
                echo '<div class="op ' . $clase . '">';
                echo '<img src="' . $rutaOpcion . '" alt="opcion" data-correcta="' . $esCorrecta . '">';
                echo '</div>';
                $indice++;
            }
        }
    } else {
        echo '<p>Opciones no encontradas</p>';
    }

    // Determinar siguiente tipo
    $siguiente_tipo = ($tipo_actual === 'A') ? 'B' : 'fin';
} else {
    echo '<div class="que">Pregunta no encontrada</div>';
    $siguiente_tipo = 'fin';
}

$conn->close();
?>

<script>
document.querySelectorAll('.op img').forEach(function(img) {
    img.addEventListener('click', function() {
        const correcta = this.getAttribute('data-correcta');

        // Limpiar bordes anteriores
        document.querySelectorAll('.op img').forEach(function(i) {
            i.style.border = 'none';
        });

        if (correcta === "1") {
            this.style.border = '4px solid green';

            setTimeout(function() {
                <?php if ($siguiente_tipo === 'fin') : ?>
                    window.location.href = 'success1-1.html';
                <?php else : ?>
                    window.location.href = 'level01.php?tipo=<?php echo $siguiente_tipo; ?>';
                <?php endif; ?>
            }, 1000);
        } else {
            this.style.border = '4px solid red';
            setTimeout(() => {
                this.style.border = 'none';
            }, 300);
        }
    });
});
</script>