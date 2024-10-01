<?php
session_start();
require 'conexion.php';
require 'models/Kurtsoa.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id']; // Obtén el ID del curso a eliminar

$db = new Conexion();
$conn = $db->getConnection();
$kurtso = new Kurtsoa($conn);

if ($kurtso->deleteById($id)) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Errorea kurtsoa ezabatzerakoan.";
}
?>
