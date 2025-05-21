<?php
session_start();

// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$correoError = $passwordError = $error = null;
$correo = '';

if (!empty($_POST)) {
    $correo = $_POST['correo'];
    $password = $_POST['contrasena'];
    $valid = true;

    if (empty($correo)) {
        $correoError = 'Por favor ingresa tu correo';
        $valid = false;
    }

    if (empty($password)) {
        $passwordError = 'Por favor ingresa tu contraseña';
        $valid = false;
    }

    if ($valid) {
        // Conexión con usuario correcto
        $conn = new mysqli("localhost", "TC2005B_601_1", "pAssWd_194742", "R_601_1");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT ID_usuario, nombre, contraseña FROM Usuario WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $nombre, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $nombre;
                $stmt->close();
                $conn->close();
                // Redirige al mapa si la contraseña es correcta
                header("Location: worldmap.html");
                exit;
            } else {
                $error = "❌ Contraseña incorrecta";
            }
        } else {
            $error = "❌ Usuario no encontrado";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>

  <div class="container">
    <h2>Sign In</h2>

    <?php if ($error): ?>
      <p class="help-inline" style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="login.php" method="post">
      <h4>E-mail</h4>
      <input type="text" name="correo" placeholder="A0XXXXXXX" value="<?php echo htmlspecialchars($correo); ?>" required>
      <?php if ($correoError): ?>
        <span class="help-inline" style="color:red;"><?php echo $correoError; ?></span>
      <?php endif; ?>

      <h4>Password</h4>
      <input type="password" name="contrasena" placeholder="XXX" required>
      <?php if ($passwordError): ?>
        <span class="help-inline" style="color:red;"><?php echo $passwordError; ?></span>
      <?php endif; ?>

      <br><br>
      <button type="submit">Let's go!</button>
    </form>

    <h4>or <a href="register.php" class="signup-link">sign up</a></h4>
  </div>

  <img src="assets/img/img_login/waves.svg" class="waves">
</body>
</html>