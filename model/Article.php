<?php
// Article.php - Modèle

class Article
{
    private $id;
    private $title;
    private $content;
    private $category;
    private $author;
    private $publication_date;
    private $status;
    private $pdo;

    // Constructeur de la classe
    public function __construct($pdo, $id = "", $title = "", $content = "", $category = "", $author = "", $publication_date = "", $status = "")
    {
        $this->pdo = $pdo;

        // Initialisation des propriétés si elles sont passées
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
        $this->author = $author;
        $this->publication_date = $publication_date;
        $this->status = $status;
    }

    // Getters et Setters pour chaque propriété
    
    // Getter et Setter pour ID
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter et Setter pour Title
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    // Getter et Setter pour Content
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    // Getter et Setter pour Category
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    // Getter et Setter pour Author
    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    // Getter et Setter pour Publication Date
    public function getPublicationDate()
    {
        return $this->publication_date;
    }

    public function setPublicationDate($publication_date)
    {
        $this->publication_date = $publication_date;
    }

    // Getter et Setter pour Status
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    // Méthode pour ajouter un article dans la base de données
    public function addArticle()
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO articles (title, content, category, author, publication_date, status) 
                                         VALUES (:title, :content, :category, :author, :publication_date, :status)");

            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':content', $this->content);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':publication_date', $this->publication_date);
            $stmt->bindParam(':status', $this->status);

            $stmt->execute();

            return true;  // Succès de l'ajout
        } catch (PDOException $e) {
            // Erreur dans l'insertion
            return "Erreur lors de l'ajout de l'article: " . $e->getMessage();
        }
    }

    // Méthode pour récupérer tous les articles
   // Méthode pour récupérer tous les articles
public function getAllArticles()
{
    try {
        $stmt = $this->pdo->prepare("SELECT * FROM articles");
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $articles;
    } catch (PDOException $e) {
        // Log the error message
        error_log("Error retrieving articles: " . $e->getMessage());
        return "An error occurred while fetching articles. Please try again later.";
    }
}

// Méthode pour récupérer un article par son ID
public function getArticleById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function deleteArticleById($id) {
    $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = ?");
    return $stmt->execute([$id]);
}

}
?>
