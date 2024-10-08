<?php

class Kurtsoa {
    private $conn;
    private $table_name = "kurtsoak";
    public $id;
    public $izena;
    public $deskripzioa;
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

    public function getDeskripzioa() {
        return $this->deskripzioa;
    }

    // Setters
    public function setIzena($izena) {
        $this->izena = $izena;
    }

    public function setDeskripzioa($deskripzioa) {
        $this->deskripzioa = $deskripzioa;
    }
    public function getAll() {
        $query = "SELECT * FROM kurtsoak";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//PARAMETROAK ERABILITA SEGURTASUNA BERMATZEKO    
    public function getById($id) {
        $query = "SELECT * FROM kurtsoak WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
     //KURTSOA EGUNERATU ID-AREKIN
    public function updateById($id) {
        $query = "UPDATE kurtsoak SET izena = :izena, deskripzioa = :deskripzioa WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':izena', $this->izena);
        $stmt->bindParam(':deskripzioa', $this->deskripzioa);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    //KURTSOA BORRATU ID-AREKIN
    public function deleteById($id) {
        $query = "DELETE FROM kurtsoak WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // CRUD OPERAZIOAK
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET izena=:izena, deskripzioa=:deskripzioa";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":izena", $this->izena);
        $stmt->bindParam(":deskripzioa", $this->deskripzioa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET izena=:izena, deskripzioa=:deskripzioa WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":izena", $this->izena);
        $stmt->bindParam(":deskripzioa", $this->deskripzioa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
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
