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
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Récupérer le dernier article ajouté
try {
    $stmt = $pdo->query("SELECT * FROM articles ORDER BY id DESC LIMIT 1");
    $lastArticle = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch last article error: " . $e->getMessage());
    $lastArticle = null;
}

// Récupérer tous les autres articles
try {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id != ? ORDER BY created_at DESC");
    $stmt->execute([$lastArticle ? $lastArticle['id'] : 0]);
    $otherArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetch other articles error: " . $e->getMessage());
    $otherArticles = [];
}

// Fonction pour vérifier si l'article peut être modifié
function canEditArticle($article, $userId) {
    // Si l'utilisateur n'est pas connecté, il ne peut rien modifier
    if (!$userId) return false;
    
    // Si l'article a un user_id et qu'il correspond à l'utilisateur connecté
    if (isset($article['user_id']) && $article['user_id'] == $userId) return true;
    
    return false;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Liste des Articles - Give4You</title>
    
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/animate.css">
    <link rel="stylesheet" href="../../../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../../../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../../../css/magnific-popup.css">
    <link rel="stylesheet" href="../../../css/aos.css">
    <link rel="stylesheet" href="../../../css/ionicons.min.css">
    <link rel="stylesheet" href="../../../css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="../../../css/jquery.timepicker.css">
    <link rel="stylesheet" href="../../../css/flaticon.css">
    <link rel="stylesheet" href="../../../css/icomoon.css">
    <link rel="stylesheet" href="../../../css/fancybox.min.css">
    <link rel="stylesheet" href="../../../css/bootstrap.css">
    <link rel="stylesheet" href="../../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .article-card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        .article-header {
            background-color: #5c5c5c;
            padding: 15px;
            border-bottom: none;
            color: white;
        }
        .article-body {
            padding: 20px;
            background-color: white;
        }
        .article-footer {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-top: 1px solid #eee;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .status-published {
            background-color: #27ae60;
            color: white;
        }
        .status-draft {
            background-color: #f39c12;
            color: white;
        }
        .status-archived {
            background-color: #7f8c8d;
            color: white;
        }
        .action-buttons {
            margin-top: 10px;
        }
        .action-buttons a {
            margin-right: 10px;
        }
        .btn-info {
            background-color: #f5ca4f;
            border-color: #f5ca4f;
            color: white;
        }
        .btn-info:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            color: white;
        }
        .btn-warning {
            background-color: #e67e22;
            border-color: #e67e22;
            color: white;
        }
        .btn-warning:hover {
            background-color: #d35400;
            border-color: #d35400;
            color: white;
        }
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            color: white;
        }
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        }
        .modal-header {
            background-color: transparent;
            color: white;
            border-bottom: none;
            border-radius: 15px 15px 0 0;
        }
        .modal-header .close {
            color: white;
            text-shadow: none;
            opacity: 0.8;
        }
        .modal-header .close:hover {
            opacity: 1;
        }
        .modal-footer {
            background-color: transparent;
            border-top: 1px solid #eee;
            border-radius: 0 0 15px 15px;
        }
        .article-details {
            padding: 20px;
        }
        .article-details p {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .article-details .label {
            font-weight: bold;
            color: #34495e;
        }
        .badge-info {
            background-color: #f7ca44;
        }
        .badge-secondary {
            background-color: #7f8c8d;
        }
        .btn-primary {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
        .btn-primary:hover {
            background-color: #34495e;
            border-color: #34495e;
        }
        .alert-success {
            background-color: #27ae60;
            border-color: #27ae60;
            color: white;
        }
        .alert-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
            color: white;
        }
        .alert-info {
            background-color: #5c5c5c;
            border-color: #5c5c5c;
            color: white;
        }
        
        /* Nouveaux styles pour les badges d'auteur */
        .author-badge {
            background-color: transparent;
            color: black;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-left: 5px;
        }
        .author-badge.own {
            background-color: transparent;
        }
        .article-meta {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .last-article-section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .other-articles-section {
            display: none;
        }
        .other-articles-section.show {
            display: block;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.html">Give4You</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="signup.html" class="nav-link">Signup</a></li>
                    <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="Association.html" class="nav-link">Association</a></li>
                    <li class="nav-item"><a href="donate.html" class="nav-link">Donation</a></li>
                    <li class="nav-item"><a href="articleList.php" class="nav-link">Article</a></li>
                    <li class="nav-item"><a href="Event.html" class="nav-link">Event</a></li>
                    <li class="nav-item"><a href="magasin.html" class="nav-link">E-Shop</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HEADER -->
    <div class="block-31" style="position: relative;">
        <div class="owl-carousel loop-block-31">
            <div class="block-30 block-30-sm item" style="background-image: url('../../../images/img_5.jpg');">
                <div class="container">
                    <div class="row align-items-center justify-content-center text-center">
                        <div class="col-md-7">
                            <h2 class="heading mb-5">Liste des Articles</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="site-section bg-light">
        <div class="container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-12">
                    <a href="addArticle.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter un Article
                    </a>
                </div>
            </div>

            <!-- Dernier article ajouté -->
            <?php if ($lastArticle): ?>
            <div class="last-article-section">
                <h3 class="mb-4">Votre dernier article</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card article-card">
                            <div class="article-header">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($lastArticle['title']); ?></h5>
                            </div>
                            <div class="article-body">
                                <div class="article-meta">
                                    <span class="badge badge-info"><?php echo htmlspecialchars($lastArticle['category']); ?></span>
                                    <span class="author-badge own">
                                        <?php echo htmlspecialchars($lastArticle['author']); ?>
                                        <i class="fas fa-check-circle ml-1"></i>
                                    </span>
                                </div>
                                <p class="card-text">
                                    <?php echo htmlspecialchars(substr($lastArticle['content'], 0, 150)) . (strlen($lastArticle['content']) > 150 ? '...' : ''); ?>
                                </p>
                                <div class="status-badge <?php echo 'status-' . strtolower($lastArticle['status']); ?>">
                                    <?php echo htmlspecialchars($lastArticle['status']); ?>
                                </div>
                            </div>
                            <div class="article-footer">
                                <div class="action-buttons">
                                
                                    <a href="updateArticle.php?id=<?php echo $lastArticle['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form action="deleteArticle.php" method="post" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $lastArticle['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet article ?');">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Bouton pour voir les autres articles -->
            <div class="row mb-4">
                <div class="col-md-12 text-center">
                    <button id="toggleOtherArticles" class="btn btn-primary">
                        <i class="fas fa-list"></i> Voir les autres articles
                    </button>
                </div>
            </div>

            <!-- Autres articles -->
            <div id="otherArticles" class="other-articles-section">
                <h3 class="mb-4">Autres articles</h3>
                <div class="row">
                    <?php foreach ($otherArticles as $article): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card article-card">
                                <div class="article-header">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($article['title']); ?></h5>
                                </div>
                                <div class="article-body">
                                    <div class="article-meta">
                                        <span class="badge badge-info"><?php echo htmlspecialchars($article['category']); ?></span>
                                        <span class="author-badge">
                                            <?php echo htmlspecialchars($article['author']); ?>
                                        </span>
                                    </div>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars(substr($article['content'], 0, 150)) . (strlen($article['content']) > 150 ? '...' : ''); ?>
                                    </p>
                                    <div class="status-badge <?php echo 'status-' . strtolower($article['status']); ?>">
                                        <?php echo htmlspecialchars($article['status']); ?>
                                    </div>
                                </div>
                                <div class="article-footer">
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#articleModal<?php echo $article['id']; ?>">
                                            <i class="fas fa-eye"></i> Voir détails
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal pour les détails de l'article -->
                            <div class="modal fade" id="articleModal<?php echo $article['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="articleModalLabel<?php echo $article['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="articleModalLabel<?php echo $article['id']; ?>">
                                                <?php echo htmlspecialchars($article['title']); ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body article-details">
                                            <p><span class="label">Titre :</span> <?php echo htmlspecialchars($article['title']); ?></p>
                                            <p><span class="label">Contenu :</span> <?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                                            <p><span class="label">Catégorie :</span> <?php echo htmlspecialchars($article['category']); ?></p>
                                            <p><span class="label">Auteur :</span> <?php echo htmlspecialchars($article['author']); ?></p>
                                            <p><span class="label">Statut :</span> <?php echo htmlspecialchars($article['status']); ?></p>
                                            <p><span class="label">Date de création :</span> <?php echo htmlspecialchars($article['created_at']); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h3 class="heading-section border-bottom pb-2 mb-3">À propos de nous</h3>
                    <p class="lead fs-6">Chez <span class="text-primary fw-bold">Give4You</span>, nous croyons en la puissance de la générosité...</p>
                    <button class="btn btn-outline-primary mt-2">En savoir plus</button>
                </div>
                <div class="col-md-4">
                    <h3 class="heading-section border-bottom pb-2 mb-3">Contactez-nous</h3>
                    <ul class="list-unstyled contact-list">
                        <li><i class="fas fa-envelope me-2 text-primary"></i> <a href="mailto:give4you@contact.com" class="text-white">give4you@contact.com</a></li>
                        <li><i class="fas fa-phone me-2 text-primary"></i> <a href="tel:+21653247404" class="text-white">(+216) 53 247 404</a></li>
                        <li><i class="fas fa-map-marker-alt me-2 text-primary"></i> B.P. 160, pôle Technologique, Z.I. Chotrana II, 2083</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h3 class="heading-section border-bottom pb-2 mb-3">Suivez-nous</h3>
                    <div class="social-icons d-flex gap-3 mb-4">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mt-4 pt-3 border-top">
                <div class="col-md-6">
                    <p>&copy; 2025 Give4You. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white">Mentions légales</a> |
                    <a href="#" class="text-white">Politique de confidentialité</a> |
                    <a href="#" class="text-white">CGU</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#F96D00" />
        </svg>
    </div>

    <!-- Scripts -->
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/jquery-migrate-3.0.1.min.js"></script>
    <script src="../../../js/popper.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/jquery.easing.1.3.js"></script>
    <script src="../../../js/jquery.waypoints.min.js"></script>
    <script src="../../../js/jquery.stellar.min.js"></script>
    <script src="../../../js/owl.carousel.min.js"></script>
    <script src="../../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../../js/bootstrap-datepicker.js"></script>
    <script src="../../../js/jquery.fancybox.min.js"></script>
    <script src="../../../js/aos.js"></script>
    <script src="../../../js/jquery.animateNumber.min.js"></script>
    <script src="../../../js/main.js"></script>

    <script>
        // Script pour masquer le loader une fois la page chargée
        window.addEventListener('load', function() {
            const loader = document.getElementById('ftco-loader');
            if (loader) {
                loader.classList.add('hide');
            }
        });

        // Script pour afficher/masquer les autres articles
        document.getElementById('toggleOtherArticles').addEventListener('click', function() {
            const otherArticles = document.getElementById('otherArticles');
            otherArticles.classList.toggle('show');
            this.innerHTML = otherArticles.classList.contains('show') ? 
                '<i class="fas fa-times"></i> Masquer les autres articles' : 
                '<i class="fas fa-list"></i> Voir les autres articles';
        });
    </script>
</body>
</html>