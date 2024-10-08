<?php
//HASI SESIOA $_SESSION ALDAGAIAK ERABILI AHAL IZATEKO
session_start();
require 'conexion.php';

//LOGEATUTA EZ BADAGO LOGINEARA BIDALI SEGURTASUNA BERMATZEKO
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}
//DBRAKO KONEXIOA SORTU ETA KONEKTATU
$db = new Conexion();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //BORRATZEKO MATRIKULAREN INFORMAZIOA JASO
    $usuario_id = $_POST['usuario_id'];
    $kurtso_id = $_POST['kurtso_id'];
    //QUERYA PRESTATU
    $query = "DELETE FROM usuariokurtsoak WHERE usuario_id = :usuario_id AND kurtso_id = :kurtso_id";
    $stmt = $conn->prepare($query);
     //PARAMETROAK ERABILI SEGURTASUNA BERMATZEKO
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':kurtso_id', $kurtso_id);
   
    //QUERY-A EXEKUTATU
    if ($stmt->execute()) {
        header("Location: dashboard.php?message=Usuario eliminado del curso con éxito.");
    } else {
        //ERROREA BADAGO DASHBOARDERA BIDALE ERRORE MEZUAKIN
        header("Location: dashboard.php?message=Error al eliminar usuario del curso.");
    }
    exit;
}
?>
