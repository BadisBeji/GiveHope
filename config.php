<?php
class Database {
    private $host = "localhost";
    private $db_name = "givehope";
    private $username = "root";
    private $password = ""; // Change selon ton mot de passe
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", 
                                  $this->username, 
                                  $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
