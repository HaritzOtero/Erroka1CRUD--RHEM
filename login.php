<?php
// BEHAR DANA INPORTATU
session_start();
require 'conexion.php';
require 'models/Usuario.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //FORMULARIOEN DATUAK ALDAGAIETAN GORDE
    $email = $_POST['email'];
    $pasahitza = $_POST['pasahitza'];
    //KONEXIOA SORTU
    $db = new Conexion();
    $conn = $db->getConnection();
    $usuario = new Usuario($conn);
    
    // QUERY-a parametroekin segurtasuna bermatzeko.
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    //QUERY-A EXEKUTATU
    $stmt->execute();

    //GMAILA EZ BADAGO ERREGISTRATUTA SESIOKO DATUAK GORDE
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // SATRUTAKO PASAHITZA DB-K HASHAREKIN KONPARATU
        if (password_verify($pasahitza, $row['pasahitza'])) {
            //PASAHITZA BERDINA BADA SESIOKIDATUAK GORDE
            $_SESSION['user_id'] = $row['id'];// HAMEN USUARIO ID
            $_SESSION['user_type'] = $row['mota']; // HAMEN USUARIO MOTA
            $_SESSION['user_name'] = $row['izena']; // HAMEN IZENA GORDETZEN DA
            $_SESSION['user_lastname'] = $row['abizena']; //HAMEN USUARIO ABIZENA GORDETZEN DA BESTE ORRIALDEETAN ERABILTZEKO SESIOA ITXI ARTE
            header("Location: dashboard.php");
            exit;
        } else {
            //PASAHITZAREN HASHA ETA DATUBASEKOA EZBERDINA BADA ERROREA ERAKUTSI
            $error = "Pasahitz okerra.";
        }
    } else {
        //GMAILA ERREGISTRATTA BADAGO ERROREA ERAKUTSI
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <!-- LOGIN DIVA-A -->
    <div class="login-container">
        <h2>Saioa Hasi</h2>
        <!-- ERROREEN DIV-A -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="pasahitza" required placeholder="Pasahitza">
            <button type="submit">Hasi saioa</button>
            <a href="register.php">Konturik ez? Erregistratu.</a>
        </form>
        <br>
        <!-- INDEXERA ERAMATEN DUEN ETXE IKONOA-->
        <a href="index.php" class="home-icon">
            <i class="fas fa-home"></i>
        </a>
    </div>
</body>

</html>
