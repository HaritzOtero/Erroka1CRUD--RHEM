<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$db = new Conexion();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $kurtso_id = $_POST['kurtso_id'];

    $query = "DELETE FROM usuariokurtsoak WHERE usuario_id = :usuario_id AND kurtso_id = :kurtso_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':kurtso_id', $kurtso_id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php?message=Usuario eliminado del curso con éxito.");
    } else {
        header("Location: dashboard.php?message=Error al eliminar usuario del curso.");
    }
    exit;
}
?>
