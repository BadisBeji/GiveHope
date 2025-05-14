<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../Model/Produit.php');
if (isset($_GET['action']) && $_GET['action'] === 'deleteProduit' && isset($_GET['id'])) {
    $controller = new ProduitController();
    $controller->deleteProduit($_GET['id']);
}


class ProduitController
{   public function getAllProduits()
    {
        $db = Database::connect();
        $sql = "SELECT * FROM produits";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteProduit($id)
{
    $db = Database::connect();
    $sql = "DELETE FROM produits WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
    header("Location: ../vendeur.php");
    exit;
}
    
    // Récupérer un seul produit par ID
    public function getProduitById($id) {
        $db = Database::connect();
        $sql = "SELECT * FROM produits WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter un produit à la base
public function addProduit($produit)
{
    //var_dump($produit);
    $sql = "INSERT INTO produits (nom, description, prix, categorie, stock, image) 
            VALUES (:nom, :description, :prix, :categorie, :stock, :image)";

    $db = Database::connect();

    try {
        $query = $db->prepare($sql);
        $query->execute([
            'nom' => $produit->getNom(),
            'description' => $produit->getDescription(),
            'prix' => $produit->getPrix(),
            'categorie' => $produit->getCategorie(),
            'stock' => $produit->getStock(),
            'image' => $produit->getImage()
        ]);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}

    // Modifier un produit existant
    public function updateProduit($produit, $id) {
        $db = Database::connect();
        $sql = "UPDATE produits 
                SET nom = :nom, description = :description, prix = :prix, categorie = :categorie, stock = :stock, image = :image 
                WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'nom' => $produit->getNom(),
            'description' => $produit->getDescription(),
            'prix' => $produit->getPrix(),
            'categorie' => $produit->getCategorie(),
            'stock' => $produit->getStock(),
            'image' => $produit->getImage(),
            'id' => $id
        ]);
    }
}