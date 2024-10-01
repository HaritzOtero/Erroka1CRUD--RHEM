<?php
session_start();
require 'conexion.php';
require 'models/Kurtsoa.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = $_POST['izena'];
    $deskripzioa = $_POST['deskripzioa'];

    // Validación básica
    if (empty($izena) || empty($deskripzioa)) {
        $error = "Formulario osoa bete.";
    } else {
        $db = new Conexion();
        $conn = $db->getConnection();
        $kurtso = new Kurtsoa($conn);

        // Guarda el nuevo curso
        $kurtso->setIzena($izena);
        $kurtso->setDeskripzioa($deskripzioa);

        if ($kurtso->create()) {
            header("Location: dashboard.php"); // Redirigir al dashboard si el registro es exitoso
            exit;
        } else {
            $error = "Errorea kurtsoa gehitzerakoan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurtsoa gehitu</title>
    <link rel="stylesheet" href="assets/css/Kgehitu.css">
</head>
<body>
    <div class="form-container">
        <h2>Kurtsoa gehitu</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="izena" required placeholder="Kurtso izena">
            <input type="text" name="deskripzioa" required placeholder="Deskripzioa">
            <button type="submit">Gehitu</button>
        </form>
    </div>
</body>
</html>
