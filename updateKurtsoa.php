<?php
//INPORTATU KLASEA ETA KONEXIOA
session_start();
require 'conexion.php';
require 'models/Kurtsoa.php';
//LOGEATUTA DAGOEN ALA EZ KONPROBATU ETA EZ BADAGO LOGINERA BIDALI, GOBUSTERRAK EBITATZEKO

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id']; //AKTUALIZATU BEHAR DEN KURTSOAREN IDEA POST-ETIK GET-AREN IDA HARTU

$db = new Conexion();
$conn = $db->getConnection();
$kurtso = new Kurtsoa($conn);

//KURTSOA HARTU
$current_kurtso = $kurtso->getById($id);
//FORMULARIOAN SUMBIT EMATERAKOAN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //ALDAGAIETAN GORDE FORMULARIOAREN INFORMAZIOA
    $izena = $_POST['izena'];
    $deskripzioa = $_POST['deskripzioa'];

    //KURTSOAREN DATUAK AKTUALIZATU FORMULARIOAREN DATUEKIN
    $kurtso->setIzena($izena);
    $kurtso->setDeskripzioa($deskripzioa);

    if ($kurtso->updateById($id)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Error al actualizar el curso.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurtsoa editatu</title>
    <link rel="stylesheet" href="assets/css/updateK.css">
</head>
<body>
    <div class="form-container">
        <h2>Kurtso edizioa</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <!-- KURTSOAREN DATUAK AKTUALIZATU BAINO LEHENGO DATUEKIN BETETA-->
        <form method="POST" action="">
            <input type="text" name="izena" value="<?php echo $current_kurtso['izena']; ?>" required placeholder="Izena">
            <input type="text" name="deskripzioa" value="<?php echo $current_kurtso['deskripzioa']; ?>" required placeholder="Deskripzioa">
            <button type="submit">Eguneratu</button>
        </form>
    </div>
</body>
</html>
