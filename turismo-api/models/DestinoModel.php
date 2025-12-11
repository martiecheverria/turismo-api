<?php
require_once 'config.php';

class DestinoModel {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    public function getAll($limit = null, $offset = null, $regionFilter = null, $sortField = null, $sortOrder = null) {
        $sql = "SELECT d.*, r.nombre as region_nombre FROM destinos d JOIN regiones r ON d.id_region_fk = r.id_region";
        $params = [];

        if ($regionFilter != null) {
            $sql .= " WHERE d.id_region_fk = ?";
            $params[] = $regionFilter;
        }


        if ($sortField != null && $sortOrder != null) {
            $sql .= " ORDER BY $sortField $sortOrder";
        }

        if ($limit != null) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset != null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }

        $query = $this->db->prepare($sql);
        $query->execute($params);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }


    public function getDestinoById($id) {
        $query = $this->db->prepare('SELECT * FROM destinos WHERE id_destino = ?');
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }


    public function getColumnas() {
        $query = $this->db->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = 'destinos'");
        $query->execute([':db' => DB_NAME]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }


    public function insertDestino($nombre, $descripcion, $imagen_url, $id_region) {
        $query = $this->db->prepare('INSERT INTO destinos (nombre, descripcion, imagen_url, id_region_fk) VALUES (?, ?, ?, ?)');
        $query->execute([$nombre, $descripcion, $imagen_url, $id_region]);
        return $this->db->lastInsertId();
    }


    public function deleteDestino($id) {
        $query = $this->db->prepare('DELETE FROM destinos WHERE id_destino = ?');
        $query->execute([$id]);
    }


    public function updateDestino($id, $nombre, $descripcion, $imagen_url, $id_region) {
        $query = $this->db->prepare('UPDATE destinos SET nombre = ?, descripcion = ?, imagen_url = ?, id_region_fk = ? WHERE id_destino = ?');
        $query->execute([$nombre, $descripcion, $imagen_url, $id_region, $id]);
    }
}