<?php
// Affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
require_once __DIR__ . '/../../../config/config.php';  // Connexion à la base de données
require_once __DIR__ . '/../../Models/Article.php';    // Classe Article

// Récupération de tous les articles
$article = new Article($pdo);
$articles = $article->getAllArticles();

// Afficher un message de confirmation si l'article a été supprimé
$message = '';
if (isset($_GET['message']) && $_GET['message'] == 'deleted') {
    $message = '<p style="color: green;">L\'article a été supprimé avec succès.</p>';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; /* Example font stack */
        }

        #page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        #content-wrap {
            flex: 1; /* Allows this area to grow and push the footer down */
            margin-left: 250px; /* Space for the sidebar */
            padding-top: 20px;
            padding-bottom: 20px; /* Space before footer */
            background-color: #f8f9fa; /* Optional: background for content area */
            transition: margin-left 0.3s ease-out;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: rgb(25, 34, 74);
            color: white;
            padding-top: 30px;
            border-right: 1px solid #ccc;
            animation: slideInLeft 0.5s ease-out; /* Consider if this animation is needed on page load */
            z-index: 1000; /* Ensure sidebar is on top */
            transition: width 0.3s ease-out, transform 0.3s ease-out;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: background 0.2s;
        }

        #sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        #sidebar ul li a i { /* Assuming you might use icons */
            width: 25px;
            text-align: center;
        }

        /* Styles for forms, etc. - likely for other pages or sections. */
        /*
        .form-container {
          background-color: #fff;
          padding: 30px;
          border-radius: 10px;
          box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
          width: 100%;
        }

        .form-group {
          margin-bottom: 15px;
        }

        .form-group label {
          display: block;
          margin-bottom: 5px;
          font-weight: 500;
          color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
          width: 100%;
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
          background-color: #fff;
          color: #000;
          font-size: 16px;
          line-height: 1.5;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
          box-shadow: 0 0 0 0.25rem rgba(78, 159, 61, 0.25);
        }

        .btn {
          padding: 10px 20px;
          border: none;
          border-radius: 4px;
          cursor: pointer;
        }

        .btn-white {
          background-color: #fff;
          color: #000;
          border: 1px solid #ccc;
        }

        .btn-white:hover {
          background-color: #f0f0f0;
        }

        .text-danger {
          color: red;
          font-size: 12px;
        }
        */

        #footer {
            background-color: rgb(25, 34, 74);
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-left: 250px; /* Align with content */
            width: calc(100% - 250px); /* Adjust width to not go under sidebar */
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
            transition: margin-left 0.3s ease-out, width 0.3s ease-out;
        }

        #footer a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        #footer a:hover {
            text-decoration: underline;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            #sidebar {
                width: 100%;
                position: relative; /* Becomes part of the normal flow */
                height: auto; /* Adjust height */
                border-right: none;
                animation: none; /* Disable animation when stacked */
                 /* You might want to hide it by default and toggle with a button on mobile */
                /* transform: translateX(-100%); */
            }

            /* Example: If you have a toggle button for mobile sidebar */
            /* body.sidebar-open #sidebar {
                transform: translateX(0);
            } */

            #content-wrap {
                margin-left: 0; /* Content takes full width */
            }

            #footer {
                margin-left: 0;
                width: 100%;
            }

            /* Adjust table for smaller screens if needed */
            .table {
                font-size: 0.9rem;
            }
            .table th, .table td {
                padding: 0.5rem;
            }
            .btn { /* Smaller buttons on mobile */
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>

<div id="sidebar">
    <ul>
        <li><a href="#"> Dashboard</a></li>
        <li><a href="#">Articles</a></li>
        <li><a href="#">Catégories</a></li>
        <li><a href="#">Utilisateurs</a></li>
        <li><a href="#">Paramètres</a></li>
    </ul>
</div>

<div id="page-container">
    <div id="content-wrap">
        <div class="container"> <h1 class="mt-4">Liste des articles</h1>
            <?= $message; // Display success/error messages ?>

            <div class="table-responsive"> <table class="table table-bordered table-hover"> <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Contenu (Extrait)</th>
                            <th>Catégorie</th>
                            <th>Auteur</th>
                            <th>Date de publication</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($articles && is_array($articles) && count($articles) > 0): ?>
                            <?php foreach ($articles as $art): ?>
                                <tr>
                                    <td><?= htmlspecialchars($art['id']) ?></td>
                                    <td><?= htmlspecialchars($art['title']) ?></td>
                                    <td><?= htmlspecialchars(substr($art['content'], 0, 100)) . (strlen($art['content']) > 100 ? '...' : '') ?></td>
                                    <td><?= htmlspecialchars($art['category']) ?></td>
                                    <td><?= htmlspecialchars($art['author']) ?></td>
                                    <td><?= htmlspecialchars(date("d/m/Y H:i", strtotime($art['publication_date']))) // Example date formatting ?></td>
                                    <td><?= htmlspecialchars($art['status']) ?></td>
                                    <td>
                                        <a href="articleDetails.php?id=<?= $art['id'] ?>" class="btn btn-info btn-sm">Voir</a>
                                        <form action="deleteArticle.php" method="post" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                            <input type="hidden" name="id" value="<?= $art['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm delete">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Aucun article trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div> </div> <footer id="footer">
        <p>&copy; <?php echo date("Y"); ?> Give4You. Tous droits réservés.</p>
        <p>
            <a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a> | <a href="#">Conditions d'utilisation</a>
        </p>
    </footer>
</div> <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>