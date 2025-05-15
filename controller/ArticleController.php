<?php
// ArticleController.php

// Connexion à la base de données
$host = 'localhost';
$dbname = 'givehope';
$username = 'root'; // à adapter selon ton environnement
$password = ''; // à adapter selon ton environnement

try {
    // Création de la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

class ArticleController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour ajouter un article
    public function addArticle() {
        // Vérifier si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $category = $_POST['category'] ?? '';
            $author = $_POST['author'] ?? '';
            $publication_date = $_POST['publication_date'] ?? '';
            $status = $_POST['status'] ?? '';

            // Validation simple (à adapter selon les besoins)
            $errors = [];
            if (empty($title)) {
                $errors[] = "Le titre est requis.";
            }
            if (empty($content)) {
                $errors[] = "Le contenu est requis.";
            }
            if (empty($category)) {
                $errors[] = "La catégorie est requise.";
            }
            if (empty($author)) {
                $errors[] = "L'auteur est requis.";
            }
            if (empty($publication_date)) {
                $errors[] = "La date de publication est requise.";
            }
            if (empty($status)) {
                $errors[] = "Le statut est requis.";
            }

            // Si il n'y a pas d'erreurs, insertion dans la base de données
            if (empty($errors)) {
                try {
                    // Requête pour insérer l'article
                    $stmt = $this->pdo->prepare("INSERT INTO articles (title, content, category, author, publication_date, status) 
                                                   VALUES (:title, :content, :category, :author, :publication_date, :status)");

                    // Lier les paramètres
                    $stmt->bindParam(':title', $title);
                    $stmt->bindParam(':content', $content);
                    $stmt->bindParam(':category', $category);
                    $stmt->bindParam(':author', $author);
                    $stmt->bindParam(':publication_date', $publication_date);
                    $stmt->bindParam(':status', $status);

                    // Exécution de la requête
                    $stmt->execute();

                    // Message de succès
                    echo "<p>Article ajouté avec succès !</p>";

                } catch (PDOException $e) {
                    echo "Erreur lors de l'insertion : " . $e->getMessage();
                }
            } else {
                // Affichage des erreurs
                foreach ($errors as $error) {
                    echo "<p style='color:red;'>$error</p>";
                }
            }
        }
    }

    // Méthode pour supprimer un article
    public function deleteArticle($id) {
        // Préparer la requête de suppression
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Exécuter la requête et retourner le résultat
        return $stmt->execute();
    }
}

// Créer une instance du contrôleur
$articleController = new ArticleController($pdo);

// Appeler la méthode pour ajouter un article si le formulaire est soumis
$articleController->addArticle();
?>
