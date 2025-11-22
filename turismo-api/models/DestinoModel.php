<?php

class DestinoModel {

    private $db;

    public function __construct() {
        require_once 'config.php';
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
        } catch (PDOException $e) {
            if ($e->getCode() == 1049) { // Código de error SQL para "Unknown database"
                header("Location: " . BASE_URL . "install.php");
                die();
            } else {
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }
    }

    public function getAllDestinos() {
        $query = $this->db->prepare("SELECT * FROM destinos");
        $query->execute();
        $destinos = $query->fetchAll(PDO::FETCH_OBJ);
        return $destinos;
    }


    public function getDestinosByRegion($id_region) {
        $query = $this->db->prepare("SELECT * FROM destinos WHERE id_region_fk = ?");
        
        $query->execute([$id_region]); 

        $destinos = $query->fetchAll(PDO::FETCH_OBJ);
        
        return $destinos;
    }

    public function getAllDestinosConRegion() {
        $query = $this->db->prepare(
            "SELECT d.*, r.nombre as region_nombre 
             FROM destinos d 
             JOIN regiones r ON d.id_region_fk = r.id_region"
        );
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    

    public function insertDestino($nombre, $descripcion, $imagen_url, $id_region) {
        $query = $this->db->prepare('INSERT INTO destinos (nombre, descripcion, imagen_url, id_region_fk) VALUES (?, ?, ?, ?)');
        $query->execute([$nombre, $descripcion, $imagen_url, $id_region]);
        return $this->db->lastInsertId();
    }

    public function deleteDestino($id_destino) {
        $query = $this->db->prepare('DELETE FROM destinos WHERE id_destino = ?');
        $query->execute([$id_destino]);
    }
    
    public function getDestinoById($id_destino) {
        $query = $this->db->prepare('SELECT * FROM destinos WHERE id_destino = ?');
        $query->execute([$id_destino]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function updateDestino($id_destino, $nombre, $descripcion, $imagen_url, $id_region) {
        $query = $this->db->prepare(
            'UPDATE destinos SET nombre = ?, descripcion = ?, imagen_url = ?, id_region_fk = ? WHERE id_destino = ?'
        );
        $query->execute([$nombre, $descripcion, $imagen_url, $id_region, $id_destino]);
    }
    
}    