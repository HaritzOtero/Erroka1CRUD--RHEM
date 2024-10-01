<?php
session_start();
require 'conexion.php';
require 'models/Usuario.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id']; // Obtén el ID del usuario a eliminar

$db = new Conexion();
$conn = $db->getConnection();
$usuario = new Usuario($conn);

if ($usuario->deleteById($id)) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Error al eliminar el usuario.";
}
?>
