<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=givehope", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8");
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    $_SESSION['error'] = "Erreur de connexion à la base de données. Veuillez réessayer plus tard.";
    header("Location: articleList.php");
    exit();
}

// Vérifier que la méthode est bien POST et qu'un ID a été fourni
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id']) && !empty($_POST['delete_id'])) {
    $articleId = $_POST['delete_id'];
    
    // Vérification optionnelle: s'assurer que l'article existe et peut être supprimé par l'utilisateur actuel
    try {
        // On pourrait ajouter ici une vérification pour s'assurer que l'utilisateur a le droit de supprimer cet article
        // Par exemple: AND user_id = :user_id
        $checkStmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $checkStmt->execute(['id' => $articleId]);
        $article = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$article) {
            $_SESSION['error'] = "Article introuvable.";
            header("Location: articleList.php");
            exit();
        }
        
        // Optionnel: vérifier si l'utilisateur connecté est bien l'auteur de l'article
        // Si vous avez un système d'authentification avec user_id, décommentez et adaptez le code ci-dessous
        /*
        if (isset($_SESSION['user_id']) && $article['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Vous n'avez pas l'autorisation de supprimer cet article.";
            header("Location: articleList.php");
            exit();
        }
        */
        
        // Supprimer l'article
        $deleteStmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
        $deleteStmt->execute(['id' => $articleId]);
        
        $_SESSION['success'] = "L'article a été supprimé avec succès.";
    } catch (PDOException $e) {
        error_log("Delete article error: " . $e->getMessage());
        $_SESSION['error'] = "Une erreur est survenue lors de la suppression de l'article.";
    }
} else {
    $_SESSION['error'] = "Requête invalide.";
}

// Rediriger vers la liste des articles
header("Location: articleList.php");
exit();
?>