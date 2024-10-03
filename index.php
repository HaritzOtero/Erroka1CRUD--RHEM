<?php
session_start();

// Verifica si el usuario está logueado
$loggedIn = isset($_SESSION['user_id']);
$nombreUsuario = '';
if ($loggedIn) {
    $nombreUsuario = $_SESSION['user_name'] . ' ' . $_SESSION['user_lastname']; // Nombre y apellido del usuario
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uni Rhem - Formación Profesional</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Iconos de redes sociales -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"> <!-- Actualiza el enlace de Font Awesome -->
</head>
<body>
    <!-- Navegación -->
    <nav>
        <div class="nav-left">
            <h1><a href="index.php">Uni RHEM</a></h1>
        </div>
        <div class="nav-right">
            <?php if ($loggedIn): ?>
                <div class="dropdown">
                <i class="fa-solid fa-user"></i>
                    <a href="#" class="user"><?php echo htmlspecialchars($nombreUsuario); ?></a>
                    <div class="dropdown-content">
                        <a class = "nav-a" href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Erregistratu</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="main-content">
        <div class="description">
            <h2>Ongi etorri Uni Rhem-era</h2>
            <p>Uni Rhem-en kalitate handiko lanbide-heziketa eskaintzen dugu, ikasleak lan-mundurako prestatzera bideratua. Gure ikastaro sorta zabala zure etorkizun profesionalerako beharrezkoak diren trebetasunak eskaintzeko diseinatuta dago.</p>
            <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php" class="btn">Kurtsoak ikusi</a>
            <?php } else { ?>
                <a href="dashboard.php" class="btn">Kurtsoak ikusi</a>
            <?php } ?>
        </div>
        <div class="image">
            <img src="assets/img/indexImg.webp" alt="Imagen de Uni Rhem">
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="social-media">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <p>&copy; 2024 Uni Rhem. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
