<?php

class UserModel {
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


    public function getUserByEmail($email) {
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE email = ?');
        $query->execute([$email]);
        
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function insertUser($email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = $this->db->prepare('INSERT INTO usuarios (email, password) VALUES (?, ?)');
        $query->execute([$email, $hash]);
    }
}