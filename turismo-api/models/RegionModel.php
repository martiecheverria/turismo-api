<?php
class RegionModel {
    private $db;

    public function __construct() {
        require_once 'config.php';
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
        } catch (PDOException $e) {
            if ($e->getCode() == 1049) { // Código de error SQL para "Unknown database"
                header("Location:  . BASE_URL . install.php");
                die();
            } else {
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }
    }

    public function getAllRegiones() {
        $query = $this->db->prepare("SELECT * FROM regiones");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }


    public function insertRegion($nombre, $imagen_url) {
        $query = $this->db->prepare('INSERT INTO regiones (nombre, imagen_url) VALUES (?, ?)');
        $query->execute([$nombre, $imagen_url]);
        return $this->db->lastInsertId();
    }

    public function updateRegion($id_region, $nombre, $imagen_url) {
        $query = $this->db->prepare('UPDATE regiones SET nombre = ?, imagen_url = ? WHERE id_region = ?');
        $query->execute([$nombre, $imagen_url, $id_region]);
    }

    public function deleteRegion($id_region) {
        $query = $this->db->prepare('DELETE FROM regiones WHERE id_region = ?');
        $query->execute([$id_region]);
    }

 
    public function getRegionById($id_region) {
        $query = $this->db->prepare('SELECT * FROM regiones WHERE id_region = ?');
        $query->execute([$id_region]);
        return $query->fetch(PDO::FETCH_OBJ);
    }


}