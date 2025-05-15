<?php
// Utilisation d'un try-catch pour gérer les erreurs potentielles
try {
    // Assurez-vous que ce chemin est correct par rapport à l'emplacement de display.php
    require_once __DIR__ . '/../../controller/EventController.php';

    // Initialisation du contrôleur
    $eventController = new EventController();

    // Récupération des événements avec gestion des erreurs
    $list = $eventController->listEvents();

    // Vérification que $list est bien un tableau
    if (!is_array($list)) {
        throw new Exception("La liste des événements n'est pas au format attendu");
    }
} catch (Exception $e) {
    // Journalisation de l'erreur
    error_log("Erreur lors du chargement des événements: " . $e->getMessage());
    // Initialisation d'une liste vide en cas d'erreur
    $list = [];
    $error_message = "Impossible de charger les événements. Veuillez réessayer plus tard.";
}

function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Configuration des cookies sécurisés
setcookie("myCookie", "value", [
    "samesite" => "Lax",   // or 'Strict', or 'None' (if cross-site)
    "secure" => true,     // Required if SameSite=None
    "httponly" => true
]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements | Give4You</title>
    
    <!-- CSS Bootstrap officiel -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- CSS Plugins -->
    <link rel="stylesheet" href="/charity_website/assets/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="/charity_website/assets/css/animate.css">
    <link rel="stylesheet" href="/charity_website/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/charity_website/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/charity_website/assets/css/magnific-popup.css">
    <link rel="stylesheet" href="/charity_website/assets/css/aos.css">
    <link rel="stylesheet" href="/charity_website/assets/css/ionicons.min.css">
    <link rel="stylesheet" href="/charity_website/assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="/charity_website/assets/css/jquery.timepicker.css">
    <link rel="stylesheet" href="/charity_website/assets/css/flaticon.css">
    <link rel="stylesheet" href="/charity_website/assets/css/icomoon.css">
    <link rel="stylesheet" href="/charity_website/assets/css/fancybox.min.css">
    <link rel="stylesheet" href="/charity_website/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/charity_website/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.html">Give4You</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="signup.html">Signup</a></li>
                <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="Association.html">Association</a></li>
                <li class="nav-item"><a class="nav-link" href="donate.html">Donation</a></li>
                <li class="nav-item"><a class="nav-link" href="Article.html">Article</a></li>
                <li class="nav-item active"><a class="nav-link" href="Event.html">Event</a></li>
                <li class="nav-item"><a class="nav-link" href="magasin.html">E-Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="display.php">Mes Événements</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="text-center mb-4">Nos Événements</h2>
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger text-center">
            <?= escape($error_message) ?>
        </div>
    <?php elseif (empty($list)): ?>
        <div class="text-center">
            <p>Aucun événement n'est disponible pour le moment.</p>
            <a href="add-event.php" class="btn btn-primary">Proposer un événement</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($list as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= escape($event['name'] ?? 'Sans nom') ?></h5>
                            <p class="card-text">
                                <?= strlen($event['description'] ?? '') > 100 ? escape(substr($event['description'], 0, 100)) . '...' : escape($event['description'] ?? 'Pas de description') ?>
                            </p>
                            <p><i class="fas fa-map-marker-alt"></i> <?= escape($event['location'] ?? 'Lieu non spécifié') ?></p>
                            <p><i class="fas fa-calendar"></i> <?= escape($event['start_datetime'] ?? 'Date et heure non spécifiés') ?></p>
                            <p><i class="fas fa-calendar"></i> <?= escape($event['end_datetime'] ?? 'Date et heure non spécifiés') ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <button type="button" class="btn btn-primary show-details" data-bs-toggle="modal" data-bs-target="#eventDetailsModal<?= escape($event['id']) ?>">
                                Détails
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="eventDetailsModal<?= escape($event['id']) ?>" tabindex="-1" aria-labelledby="eventDetailsModalLabel<?= escape($event['id']) ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventDetailsModalLabel<?= escape($event['id']) ?>">Détails de l'événement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h3 id="eventName<?= escape($event['id']) ?>" class="mb-3"><?= escape($event['name'] ?? 'Sans nom') ?></h3>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><i class="fas fa-map-marker-alt"></i> <span id="eventLocation<?= escape($event['id']) ?>"><?= escape($event['location'] ?? 'Lieu non spécifié') ?></span></p>
                                        <p><i class="fas fa-calendar"></i> <span id="start_datetime<?= escape($event['id']) ?>">
                                            <?php 
                                            if (!empty($event['start_datetime'])) {
                                                $date = new DateTime($event['start_datetime']);
                                                echo escape($date->format('d/m/Y H:i'));
                                            } else {
                                                echo 'Date de début non spécifiée';
                                            }
                                            ?>
                                        </span></p>
                                        <p><i class="fas fa-calendar"></i> <span id="end_datetime<?= escape($event['id']) ?>">
                                            <?php 
                                            if (!empty($event['end_datetime'])) {
                                                $date = new DateTime($event['end_datetime']);
                                                echo escape($date->format('d/m/Y H:i'));
                                            } else {
                                                echo 'Date de fin non spécifiée';
                                            }
                                            ?>
                                        </span></p>
                                    </div>        
                                    <div class="col-md-6">
                                        <p><i class="fas fa-users"></i> <span id="eventOrganizer<?= escape($event['id']) ?>"><?= escape($event['organizer'] ?? 'Organisateur non spécifié') ?></span></p>
                                        <p><i class="fas fa-tag"></i> <span id="eventCategory<?= escape($event['id']) ?>"><?= escape($event['category'] ?? 'Catégorie non spécifiée') ?></span></p>
                                    </div>
                                </div>
                                <h4>Description</h4>
                                <div id="eventDescription<?= escape($event['id']) ?>" class="mb-4"><?= escape($event['description'] ?? 'Pas de description disponible') ?></div>

                                <div id="eventAdditionalInfo<?= escape($event['id']) ?>">
                                    <?php if (!empty($event['participants'])): ?>
                                        <h4>Participants</h4>
                                        <p><?= escape($event['participants']) ?> personnes inscrites</p>
                                    <?php endif; ?>
                                    <?php if (!empty($event['requirements'])): ?>
                                        <h4>Prérequis</h4>
                                        <p><?= escape($event['requirements']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                               
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="text-center mt-4">
        <a href="addEventC.php" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Ajouter un événement
        </a>
    </div>
</div>

<footer class="footer bg-dark text-white py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h3 class="heading-section border-bottom pb-2 mb-3">À propos de nous</h3>
                <p class="lead fs-6">
                    Chez <span class="text-primary fw-bold">Give4You</span>, nous
                    croyons en la puissance de la générosité pour changer des vies.
                    Notre mission est de venir en aide aux personnes dans le besoin,
                    de soutenir les communautés vulnérables et de promouvoir un monde
                    plus solidaire.
                </p>
                <button class="btn btn-outline-primary mt-2">En savoir plus</button>
            </div>

            <div class="col-md-4">
                <h3 class="heading-section border-bottom pb-2 mb-3">Contactez-nous</h3>
                <ul class="list-unstyled contact-list">
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2 text-primary"></i>
                        <a href="mailto:give4you@contact.com" class="text-white text-decoration-none hover-primary">
                            give4you@contact.com
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone me-2 text-primary"></i>
                        <a href="tel:+21653247404" class="text-white text-decoration-none hover-primary">
                            (+216) 53 247 404
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                        <span>B.P. 160, pôle Technologique, Z.I. Chotrana II, 2083</span>
                    </li>
                </ul>
                <div class="mt-3">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1595.316958293676!2d10.188458746015906!3d36.8991051049499!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12e2cb75abbb1733%3A0x557a99cdf6c13b7b!2sESPRIT%20Ecole%20Sup%C3%A9rieure%20Priv%C3%A9e%20d'Ing%C3%A9nierie%20et%20de%20Technologies!5e0!3m2!1sfr!2stn!4v1745004271543!5m2!1sfr!2stn"
                        width="300"
                        height="270"
                        style="border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div class="col-md-4">
                <h3 class="heading-section border-bottom pb-2 mb-3">Suivez-nous</h3>
                <div class="social-icons d-flex gap-3 mb-4">
                    <a href="#" class="text-white text-decoration-none">
                        <i class="fab fa-facebook fa-2x hover-effect"></i>
                    </a>
                    <a href="#" class="text-white text-decoration-none">
                        <i class="fab fa-twitter fa-2x hover-effect"></i>
                    </a>
                    <a href="#" class="text-white text-decoration-none">
                        <i class="fab fa-instagram fa-2x hover-effect"></i>
                    </a>
                    <a href="#" class="text-white text-decoration-none">
                        <i class="fab fa-linkedin fa-2x hover-effect"></i>
                    </a>
                </div>

                <div class="newsletter-form">
                    <h4 class="h5 mb-2">Restez informés</h4>
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Votre email" />
                        <button type="submit" class="btn btn-primary">OK</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-4 pt-3 border-top">
            <div class="col-md-6">
                <p class="small mb-0">© 2025 Give4You. Tous droits réservés.</p>
            </div>
            <div class="col-md-6">
                <ul class="list-inline text-md-end mb-0 small">
                    <li class="list-inline-item">
                        <a href="#" class="text-white text-decoration-none">Mentions légales</a>
                    </li>
                    <li class="list-inline-item">|</li>
                    <li class="list-inline-item">
                        <a href="#" class="text-white text-decoration-none">Politique de confidentialité</a>
                    </li>
                    <li class="list-inline-item">|</li>
                    <li class="list-inline-item">
                        <a href="#" class="text-white text-decoration-none">CGU</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<div id="ftco-loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
        <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
        <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
    </svg>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="/charity_website/assets/js/jquery-migrate-3.0.1.min.js"></script>
<script src="/charity_website/assets/js/jquery.easing.1.3.js"></script>
<script src="/charity_website/assets/js/jquery.waypoints.min.js"></script>
<script src="/charity_website/assets/js/jquery.stellar.min.js"></script>
<script src="/charity_website/assets/js/jquery.magnific-popup.min.js"></script>
<script src="/charity_website/assets/js/jquery.fancybox.min.js"></script>
<script src="/charity_website/assets/js/jquery.animateNumber.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/charity_website/assets/js/bootstrap-datepicker.js"></script>
<script src="/charity_website/assets/js/aos.js"></script>
<script src="/charity_website/assets/js/main.js"></script>
<script src="/charity_website/assets/js/Event.js"></script>
<script src="/charity_website/assets/js/validation.js"></script>
<script>
$(document).ready(function() {
    // Masquer le loader une fois que la page est chargée
    $('#ftco-loader').removeClass('show');
    
    // Désactiver Stellar sur les appareils mobiles
    if (!(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
        $.stellar({
            horizontalScrolling: false,
            verticalOffset: 0,
            responsive: true
        });
    }
});
</script>
</body>
</html>