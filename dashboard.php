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
$user_id = $_SESSION['user_id'];

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
        if ($user['id'] == $user_id) {
            $currentUserName = $user['izena']; // Aquí almacenamos el nombre del usuario
            break; // Salimos del bucle una vez que encontramos al usuario
        }
    }
}

// Obtener todos los cursos
$kurtsoak = $kurtso->getAll();

// Obtener el curso en el que el usuario está matriculado
$query = "SELECT kurtso_id FROM usuariokurtsoak WHERE usuario_id = :usuario_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':usuario_id', $user_id);
$stmt->execute();
$usuarioMatriculadoKurtsoId = $stmt->fetchColumn(); // Obtener el ID del curso en el que está matriculado
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
            <a href="index.php" class="home-icon">
                    <i class="fas fa-home"></i>
                </a>
            <h2>Kaixo, <?php echo htmlspecialchars($currentUserName); ?></h2>
            <?php if ($user_type === 'admin'): ?>
                
            <h3>Erabiltzaile gestioa</h3>
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
            <a href="register.php" class="usuarioaErregistratu">Usuarioa Erregistratu</a>
            <br>
            <br>
            <h3>Kurtso gestioa</h3>
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
            <a href="kurtsoaGehitu.php" class="kurtsoaGehitu">Kurtsoa gehitu</a>
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
                                    <form method="POST" action="matrikulatu.php">
                                        <input type="hidden" name="kurtso_id" value="<?php echo $kurtso['id']; ?>">
                                        <button type="submit" 
                                            class="<?php echo ($usuarioMatriculadoKurtsoId == $kurtso['id']) ? 'btn-matriculado' : 'btn-matricular'; ?>"
                                            <?php echo ($usuarioMatriculadoKurtsoId == $kurtso['id']) ? 'disabled' : ''; ?>>
                                            <?php echo ($usuarioMatriculadoKurtsoId == $kurtso['id']) ? 'Matrikulatuta!' : 'Matrikulatu'; ?>
                                        </button>
                                    </form>
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
