<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../Models/Article.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID d'article non spécifié.");
}

$id = (int) $_GET['id'];
$article = new Article($pdo);
$data = $article->getArticleById($id);

if (!$data) {
    die("Article non trouvé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .article-detail {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        .article-detail strong {
            display: inline-block;
            width: 180px;
        }
        .btn-back {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">📰 Détails de l'article</h2>

    <div class="article-detail">
        <p><strong>ID :</strong> <?= htmlspecialchars($data['id']) ?></p>
        <p><strong>Titre :</strong> <?= htmlspecialchars($data['title']) ?></p>
        <p><strong>Contenu :</strong><br> <?= nl2br(htmlspecialchars($data['content'])) ?></p>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($data['category']) ?></p>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($data['author']) ?></p>
        <p><strong>Date de publication :</strong> <?= htmlspecialchars($data['publication_date']) ?></p>
        <p><strong>Statut :</strong> <?= htmlspecialchars($data['status']) ?></p>
    </div>

    <a href="articleList.php" class="btn btn-secondary btn-back">← Retour à la liste</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
