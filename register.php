<?php
//SESIOA HASI $_SESSION ALDAGAIAK ERABILI AHAL IZATEKO
session_start();
require 'conexion.php';
require 'models/Usuario.php';

//FORMULARIOA BIDALTZERAKOAN...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //FORMULARIOEN DATUAK ALDAGAIETAN GORDE
    $izena = $_POST['izena'];
    $abizena = $_POST['abizena'];
    $email = $_POST['email'];
    $pasahitza = $_POST['pasahitza'];

    // SEGUN ZE EMAIL DUEN ERABILTZAILEA ADMIN EDO USER MOTA JARRI ERABILTZAILEARI
    $mota = ($email === 'admin@uni.eus') ? 'admin' : 'user';

    // OINARRIZKO BALIDAZIO BAT
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //EMAIL FORMATU TXARRA
        $error = "Emailaren formatu okerra.";
        //GUTXIENEZ 6 KARAKTERE
    } elseif (strlen($pasahitza) < 6) {
        $error = "Pasahitz 6 karaktere izan behar ditu.";
    } else {
        //DATUBASE KONEXIOA SORTU
        $db = new Conexion();
        $conn = $db->getConnection();
        $usuario = new Usuario($conn);

        // EMAILA ERREGISTRATUTA DAGOEN KONPROBATZEKO QUERYA PRESTATU
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        //QUERY-A EXEKUTATU ETA ZERAIT BUELTATZEN BADA...
        if ($stmt->rowCount() > 0) {
            //ERROREA ERAKUTSI
            $error = "Emaila erregistratuta dago.";
            //EMAILA EZ BADAGOE RREGISTRATUTA...
        } else {
            // PASAHITZA HASHEATU
            $hashed_pasahitza = password_hash($pasahitza,PASSWORD_DEFAULT);
            $hashhash = password_hash($hashed_pasahitza, PASSWORD_DEFAULT);
            // USUARIO BERRIEREN DATUAK USUARIOAN SARTU SETERREKIN
            $usuario->setIzena($izena);
            $usuario->setAbizena($abizena);
            $usuario->setEmail($email);
            $usuario->setMota($mota);
            $usuario->pasahitza = $hashed_pasahitza; 
            //USUARIOA DATUBASERA GEHITU CREATE FUNTZIOAREKIN USUARIO KLASEAN DAGOENA
            if ($usuario->create()) {
                header("Location: login.php"); // ERREGISTROA ONA BALDIN BADA LOGINERA BERBIDALI
                exit;
            } else {
                //ERROREA EGON BADA ERREGISTROAN ERROREA AZALDU
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
    <title>Erregistroa</title>
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Usuario erregistroa</h2>
         <!-- ERROREEN DIV- A SORTU ETA EA ERROREA BALDIN BADAGOEN KONPROBATU-->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
         <!-- ERREGISTRATZEKO FORMULARIOA-->
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
