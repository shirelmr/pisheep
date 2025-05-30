<?php
session_start();

// Mostrar errores (útil en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Variables y errores
$idError = $nombreError = $correoError = $passwordError = $error = null;
$id = $nombre = $correo = '';

if (!empty($_POST)) {
    $id = $_POST['ID_usuario'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['contrasena'];
    $monedas = 0;
    $valid = true;

    // Validaciones
    if (empty($id)) {
        $idError = 'Por favor ingresa tu ID';
        $valid = false;
    }
    if (empty($nombre)) {
        $nombreError = 'Por favor ingresa tu nombre';
        $valid = false;
    }
    if (empty($correo)) {
        $correoError = 'Por favor ingresa tu correo';
        $valid = false;
    }
    if (empty($password)) {
        $passwordError = 'Por favor ingresa tu contraseña';
        $valid = false;
    }

    if ($valid) {
        // Conexión con usuario correcto y contraseña
        $conn = new mysqli("localhost", "TC2005B_601_1", "pAssWd_194742", "R_601_1");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Usuario (ID_usuario, nombre, correo, contraseña, monedas) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $id, $nombre, $correo, $hashedPassword, $monedas);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: exito.html");
            exit;
        } else {
            $error = "❌ Error al registrar: " . $conn->error;
            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>

  <div class="container">
    <h2>Sign Up</h2>

    <?php if ($error): ?>
      <p class="help-inline" style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="register.php" method="post">
      <h4>ID</h4>
      <input type="text" name="ID_usuario" placeholder="A0XXXXXXX" value="<?php echo htmlspecialchars($id); ?>" required>
      <?php if ($idError): ?>
        <span class="help-inline" style="color:red;"><?php echo $idError; ?></span>
      <?php endif; ?>

      <h4>Nombre</h4>
      <input type="text" name="nombre" placeholder="Tu nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
      <?php if ($nombreError): ?>
        <span class="help-inline" style="color:red;"><?php echo $nombreError; ?></span>
      <?php endif; ?>

      <h4>E-mail</h4>
      <input type="email" name="correo" placeholder="Correo" value="<?php echo htmlspecialchars($correo); ?>" required>
      <?php if ($correoError): ?>
        <span class="help-inline" style="color:red;"><?php echo $correoError; ?></span>
      <?php endif; ?>

      <h4>Password</h4>
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <?php if ($passwordError): ?>
        <span class="help-inline" style="color:red;"><?php echo $passwordError; ?></span>
      <?php endif; ?>

      <br><br>
      <button type="submit">Registrarse</button>
    </form>

    <h4>or <a href="login.php" class="signup-link">sign in</a></h4>
  </div>

  <img src="assets/img/img_login/waves.svg" class="waves">
</body>
</html>