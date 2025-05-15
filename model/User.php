<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($nom, $prenom, $cin, $email, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, prenom, cin, email, password) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $prenom, $cin, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $nom, $prenom, $cin, $email, $password) {
        $stmt = $this->pdo->prepare("UPDATE users SET nom = ?, prenom = ?, cin = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([
            $nom, $prenom, $cin, $email, password_hash($password, PASSWORD_DEFAULT), $id
        ]);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
