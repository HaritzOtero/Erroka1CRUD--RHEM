<?php
//IMPORTATU KLASEA ETA KONEXIOA
session_start();
require 'conexion.php';
require 'models/Kurtsoa.php';

//LOGEATUTA DAGOEN ALA EZ KONPROBATU ETA EZ BADAGO LOGINERA BIDALI, GOBUSTERRAK EBITATZEKO
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //BEHAR DEN INFORMAZIOA ALDAGAIETAN GORDE
    $izena = $_POST['izena'];
    $deskripzioa = $_POST['deskripzioa'];

    // BALIDAZIO BASIKOA
    if (empty($izena) || empty($deskripzioa)) {
        $error = "Formulario osoa bete.";
    } else {
        $db = new Conexion();
        $conn = $db->getConnection();
        //KURTSO OBJETUA SORTU
        $kurtso = new Kurtsoa($conn);

        // KURTSOAREN INFORMAZIOA GORDE OBJETUAN
        $kurtso->setIzena($izena);
        $kurtso->setDeskripzioa($deskripzioa);

        //KURTSOA SORTU ESTA SORTZEN EZ BADA ERROREA IKUSI
        if ($kurtso->create()) {
            header("Location: dashboard.php"); // DASBOARDA KARGATU KURTSOA IKUSTEKO 
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
    <!-- KURTSOA GEHITZEKO FORMULARIOA -->
    <div class="form-container">
        <h2>Kurtsoa gehitu</h2>
        <!-- ERROREAK IKUSTEKO DIV-A-->
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
