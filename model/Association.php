<?php
class Association {
    private $conn;
    private $table = "association"; // Assure-toi que la table s'appelle bien "association"

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table}(name, email, description, address, phone, domain, creation_date, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'], $data['email'], $data['description'], $data['address'],
            $data['phone'], $data['domain'], $data['creation_date'], $data['country']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET name=?, email=?, description=?, address=?, phone=?, domain=?, creation_date=?, country=? WHERE id=?");
        return $stmt->execute([
            $data['name'], $data['email'], $data['description'], $data['address'],
            $data['phone'], $data['domain'], $data['creation_date'], $data['country'], $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getStatistics() {
        $query = "SELECT country, COUNT(*) AS total FROM association GROUP BY country ORDER BY total DESC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        $stats = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats[] = $row;
        }
        return $stats;
    }
    
    
    public function searchByCountry($country) {
        $query = "SELECT * FROM {$this->table} WHERE country LIKE :country";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':country', '%' . $country . '%');
        $stmt->execute();
        return $stmt;
    }
}
?>
