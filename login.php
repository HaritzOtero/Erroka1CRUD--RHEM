<?php
session_start();
require 'conexion.php';  // Asegúrate de que el archivo esté en la ruta correcta
require 'models/Usuario.php'; // Cambia la ruta si es necesario


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pasahitza = $_POST['pasahitza'];

    $db = new Conexion();
    $conn = $db->getConnection();
    $usuario = new Usuario($conn);
    
    // Aquí puedes implementar tu lógica de autenticación.
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica la contraseña (asegúrate de que la contraseña esté encriptada en la base de datos)
        if (password_verify($pasahitza, $row['pasahitza'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $row['mota']; // Por ejemplo, 'admin' o 'user'
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Pasahitz okerra.";
        }
    } else {
        $error = "Email-a ez dago erregistratuta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Saioa Hasi</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" type="password" name="pasahitza" required placeholder="Pasahitza">
            <button type="submit">Hasi saioa</button>
            <a href="register.php">Konturik ez? Erregistratu.</a>
        </form>
    </div>
    <br>
    
</body>

</html>
