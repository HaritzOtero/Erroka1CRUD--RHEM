<?php
class Conexion {
    private $host = "localhost";
    private $db_name = "erronka1_rhem";
    private $username = "root"; // Cambia si es necesario
    private $password = ""; // Cambia si es necesario
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
