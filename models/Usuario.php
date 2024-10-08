<?php
//USUARIO KLASEA
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $izena;
    public $abizena;
    public $email;
    public $mota;
    public $pasahitza;

    //FUNTZIOAK BESTE ARTXIBOETAN ERABLTZEKO
    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getIzena() {
        return $this->izena;
    }

    public function getAbizena() {
        return $this->abizena;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMota() {
        return $this->mota;
    }

    public function getPasahitza() {
        return $this->mota;
    }

    // Setters
    public function setIzena($izena) {
        $this->izena = $izena;
    }

    public function setAbizena($abizena) {
        $this->abizena = $abizena;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMota($mota) {
        $this->mota = $mota;
    }

    public function setPasahitza($mota) {
        $this->mota = $mota;
    }
    public function getAll() {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

//PARAMETROAK ERABILITA SEGURTASUNA BERMATZEKO    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET izena=:izena, abizena=:abizena, email=:email, mota=:mota, pasahitza=:pasahitza";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":izena", $this->izena);
        $stmt->bindParam(":abizena", $this->abizena);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mota", $this->mota);
        $stmt->bindParam(":pasahitza", $this->pasahitza);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    //IRAKURRI NAHI DEN TAULA
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    //ERABILTZAILEA AKTUALIZATU
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET izena=:izena, abizena=:abizena, email=:email, mota=:mota, pasahitza=:pasahitza WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":izena", $this->izena);
        $stmt->bindParam(":abizena", $this->abizena);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mota", $this->mota);
        $stmt->bindParam(":pasahitza", $this->pasahitza);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    //ERABILTZAILEA BILATU ID-AREKIN
    public function getById($id) {
        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //ERABILTZAILEA EGUNERATU ID-AREKIN
    public function updateById($id) {
        $query = "UPDATE usuarios SET izena = :izena, abizena = :abizena, email = :email, mota = :mota WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':izena', $this->izena);
        $stmt->bindParam(':abizena', $this->abizena);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':mota', $this->mota);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    //ERABILTZAILEA BORRATU ID-AREKIN
    public function deleteById($id) {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
