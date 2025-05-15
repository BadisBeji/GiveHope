<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Model/Article.php';
require_once __DIR__ . '/../Controller/ArticleController.php';

// Création du contrôleur
$articleController = new ArticleController();

// Récupération de la liste des articles
$articles = $articleController->listArticles();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - GiveForYou</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f0f0f0;
        }
        .article {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Liste des Articles</h1>

    <?php if (!empty($articles)) : ?>
        <?php foreach ($articles as $article) : ?>
            <div class="article">
                <h2><?= htmlspecialchars($article['titre']) ?></h2>
                <p><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Aucun article trouvé.</p>
    <?php endif; ?>
</body>
</html>
