<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give4You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .section-block {
            background-color: #f8f9fa;
            padding: 50px 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .section-block h2 {
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Give4You</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#events">Événements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#associations">Associations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#blogs">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#eshop">E-Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#auth">Inscription/Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1>Bienvenue sur Give4You</h1>
            <p class="lead">Votre plateforme pour participer à des événements, soutenir des associations, lire des blogs, et plus encore !</p>
        </div>

        <div id="events" class="section-block">
            <h2>Événements</h2>
            <p>Découvrez et participez à divers événements.</p>
            <a href="View/FrontOffice/eventList.php" class="btn-custom">Voir les événements</a>

        <div id="associations" class="section-block">
            <h2>Associations</h2>
            <p>Soutenez des associations et leurs causes.</p>
            <a href="#" class="btn-custom">Voir les associations</a>
        </div>

        <div id="blogs" class="section-block">
            <h2>Blogs</h2>
            <p>Lisez des articles intéressants et informatifs.</p>
            <a href="#" class="btn-custom">Voir les blogs</a>
        </div>

        <div id="eshop" class="section-block">
            <h2>E-Shop</h2>
            <p>Achetez des produits et soutenez des causes.</p>
            <a href="#" class="btn-custom">Visiter le E-Shop</a>
        </div>

        <div id="auth" class="section-block">
            <h2>Inscription/Connexion</h2>
            <p>Créez un compte ou connectez-vous pour participer pleinement.</p>
            <a href="#" class="btn-custom">S'inscrire/Se connecter</a>
        </div>
    </div>

    <footer class="mt-5 py-4 bg-dark text-white">
        <div class="container text-center">
            <p class="mb-0"><i class="fas fa-heart text-danger"></i> Give4You &copy; 2024 - Tous droits réservés</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
