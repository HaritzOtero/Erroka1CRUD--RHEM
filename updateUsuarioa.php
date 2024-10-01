<?php
session_start();
require 'conexion.php';
require 'models/Usuario.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id']; // Obtén el ID del usuario a actualizar

$db = new Conexion();
$conn = $db->getConnection();
$usuario = new Usuario($conn);

// Obtener el usuario actual para prellenar el formulario
$current_user = $usuario->getById($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = $_POST['izena'];
    $abizena = $_POST['abizena'];
    $email = $_POST['email'];
    $mota = $_POST['mota'];

    // Actualizar los datos del usuario
    $usuario->setIzena($izena);
    $usuario->setAbizena($abizena);
    $usuario->setEmail($email);
    $usuario->setMota($mota);

    if ($usuario->updateById($id)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Error al actualizar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erabiltzaile eguneraketa</title>
    <link rel="stylesheet" href="assets/css/updateU.css"></head>
<body>
    <div class="form-container">
        <h2>Actualizar Usuario</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="izena" value="<?php echo $current_user['izena']; ?>" required placeholder="Nombre">
            <input type="text" name="abizena" value="<?php echo $current_user['abizena']; ?>" required placeholder="Apellido">
            <input type="email" name="email" value="<?php echo $current_user['email']; ?>" required placeholder="Email">
            <select name="mota">
                <option value="user" <?php if($current_user['mota'] == 'user') echo 'selected'; ?>>Erabiltzailea</option>
                <option value="admin" <?php if($current_user['mota'] == 'admin') echo 'selected'; ?>>Administratzailea</option>
            </select>
            <button type="submit">Eguneratu</button>
        </form>
    </div>
</body>
</html>
