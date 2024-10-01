<?php
session_start();
require 'conexion.php';
require 'models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = $_POST['izena'];
    $abizena = $_POST['abizena'];
    $email = $_POST['email'];
    $pasahitza = $_POST['pasahitza'];

    // Asignar el tipo de usuario según el email
    $mota = ($email === 'admin@uni.eus') ? 'admin' : 'user';

    // Validación básica
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Emailaren formatu okerra.";
    } elseif (strlen($pasahitza) < 6) {
        $error = "Pasahitz 6 karaktere izan behar ditu.";
    } else {
        $db = new Conexion();
        $conn = $db->getConnection();
        $usuario = new Usuario($conn);

        // Verifica si el email ya está registrado
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "Emaila erregistratuta dago.";
        } else {
            // Hash de la contraseña
            $hashed_pasahitza = password_hash($pasahitza,PASSWORD_DEFAULT);
            
            // Guarda el nuevo usuario
            $usuario->setIzena($izena);
            $usuario->setAbizena($abizena);
            $usuario->setEmail($email);
            $usuario->setMota($mota);
            $usuario->pasahitza = $hashed_pasahitza; // Asegúrate de que la clase Usuario tenga esta propiedad

            if ($usuario->create()) {
                header("Location: login.php"); // Redirigir al login si el registro es exitoso
                exit;
            } else {
                $error = "Error al registrar el usuario.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Usuario erregistroa</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="izena" required placeholder="Izena">
            <input type="text" name="abizena" required placeholder="Abizena">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="pasahitza" required placeholder="Pasahitza">
            <button type="submit">Erregistratu</button>
            <a href="login.php">Login.</a>
        </form>
    </div>
    <br>
    
</body>
</html>
