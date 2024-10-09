<?php
session_start();
require 'conexion.php';
require 'models/Usuario.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id']; // EZABATZEKO USUARIOAREN IDA HARTU POST METODOTIK GET ERABILIZ
//KONEXIOA SORTU
$db = new Conexion();
$conn = $db->getConnection();
$usuario = new Usuario($conn);
//USUARIOA BORRATU ETA BORRATZEN BADA BIDALI DASHBOARDERA
if ($usuario->deleteById($id)) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Errorea erabiltzailea ezabatzerakoan.";
}
?>
