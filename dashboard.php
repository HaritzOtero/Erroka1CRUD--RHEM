<?php
session_start();
require 'conexion.php';
require 'models/Usuario.php';
require 'models/Kurtsoa.php'; // Cambia la ruta si es necesario

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_type = $_SESSION['user_type'];

// Conexión a la base de datos
$db = new Conexion();
$conn = $db->getConnection();

$usuario = new Usuario($conn);
$kurtso = new Kurtsoa($conn);

// Obtener lista de usuarios y cursos
$usuarios = [];
$kurtsoak = [];
$currentUserName = ""; // Inicializamos la variable

if ($user_type === 'admin') {
    // Si es admin, obtener todos los usuarios
    $usuarios = $usuario->getAll();
    $currentUserName = 'Admin'; // Aquí almacenamos el nombre del usuario
} else {
    $usuarios = $usuario->getAll();
    
    // Encontrar el usuario actual
    foreach ($usuarios as $user) {
        if ($user['id'] == $_SESSION['user_id']) {
            $currentUserName = $user['izena']; // Aquí almacenamos el nombre del usuario
            break; // Salimos del bucle una vez que encontramos al usuario
        }
    }
}

// Obtener todos los cursos
$kurtsoak = $kurtso->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Iconos de redes sociales -->
</head>
<body>
<div class="bodyContent">
    <div class="dashboard-container">
        <h2>Kaixo, <?php echo htmlspecialchars($currentUserName); ?></h2>
        <?php if ($user_type === 'admin'): ?>
            <h3>Gestión de Usuarios</h3>
            <table>
                <thead>
                    <tr>
                        <th>Izena</th>
                        <th>Abizena</th>
                        <th>Email</th>
                        <th>Pasahitza (Hasheatuta)</th>
                        <th>Erabiltzaile Mota</th>
                        <th>Akzioak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['izena']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['abizena']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['pasahitza']); ?></td> <!-- Contraseña hasheada -->
                            <td><?php echo htmlspecialchars($usuario['mota']); ?></td>
                            <td>
                                <a href="updateUsuarioa.php?id=<?php echo $usuario['id']; ?>">Update</a>
                                <a href="deleteUsuarioa.php?id=<?php echo $usuario['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="register.php">Usuarioa Erregistratu</a>

            <h3>Gestión de Cursos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Izena</th>
                        <th>Deskripzioa</th>
                        <th>Akzioak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kurtsoak as $kurtso): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($kurtso['izena']); ?></td>
                            <td><?php echo htmlspecialchars($kurtso['deskripzioa']); ?></td>
                            <td>
                                <a href="updateKurtsoa.php?id=<?php echo $kurtso['id']; ?>">Update</a>
                                <a href="deleteKurtsoa.php?id=<?php echo $kurtso['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="kurtsoaGehitu.php">Añadir Curso</a>
            <a class="logout" href="logout.php">Logout</a>
        <?php else: ?>
            <h3>Cursos Disponibles</h3>
            <table>
                <thead>
                    <tr>
                        <th>Izena</th>
                        <th>Deskripzioa</th>
                        <th>Akzioak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kurtsoak as $kurtso): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($kurtso['izena']); ?></td>
                            <td><?php echo htmlspecialchars($kurtso['deskripzioa']); ?></td>
                            <td>
                                <button>Matrikulatu</button> <!-- No realiza ninguna acción por el momento -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    
</div>
</body>
</html>
