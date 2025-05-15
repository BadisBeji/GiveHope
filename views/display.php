<?php
// Utilisation d'un try-catch pour gérer les erreurs potentielles
try {
    // Assurez-vous que ce chemin est correct par rapport à l'emplacement de display.php
    require_once __DIR__ . '/../../controllers/EventController.php';

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="../../assets/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/animate.css">
    <link rel="stylesheet" href="../../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../../assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../../assets/css/magnific-popup.css">
    <link rel="stylesheet" href="../../assets/css/aos.css">
    <link rel="stylesheet" href="../../assets/css/ionicons.min.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="../../assets/css/jquery.timepicker.css">
    <link rel="stylesheet" href="../../assets/css/flaticon.css">
    <link rel="stylesheet" href="../../assets/css/icomoon.css">
    <link rel="stylesheet" href="../../assets/css/fancybox.min.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">Give4You</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="login.html" class="nav-link">Logout</a></li>
                    <li class="nav-item"><a href="../../index.php" class="nav-link">Accueil</a></li>
                    <li class="nav-item"><a href="Association.html" class="nav-link">Association</a></li>
                    <li class="nav-item"><a href="public" class="nav-link">Donation</a></li>
                    <li class="nav-item"><a href="views/front/addArticle.php" class="nav-link">Article</a></li>
                    <li class="nav-item active"><a href="views/front/display.php" class="nav-link">Événement</a></li>
                    <li class="nav-item"><a href="magasin.html" class="nav-link">E-Shop</a></li>
                    <li class="nav-item" id="my-events-nav"><a href="my-events.html" class="nav-link">Mes Événements</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="block-31" style="position: relative">
        <div class="owl-carousel loop-block-31">
            <div class="block-30 block-30-sm item" style="background-image: url('../../assets/images/fre.jpg');" data-stellar-background-ratio="0.5">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-7 text-center">
                            <h2 class="heading">Nos Événements</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger text-center">
                    <?= escape($error_message) ?>
                </div>
            <?php elseif (empty($list)): ?>
                <div class="text-center">
                    <p>Aucun événement n'est disponible pour le moment.</p>
                    <a href="addEventC.php" class="btn btn-primary">Proposer un événement</a>
                </div>
            <?php else: ?>
                <?php foreach ($list as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?= escape($event['name'] ?? 'Sans nom') ?></h5>
                                <p class="card-text">
                                    <?= strlen($event['description'] ?? '') > 100 ? escape(substr($event['description'], 0, 100)) . '...' : escape($event['description'] ?? 'Pas de description') ?>
                                </p>
                                <p class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> <?= escape($event['location'] ?? 'Lieu non spécifié') ?></p>
                                <p class="mb-2"><i class="fas fa-calendar text-primary me-2"></i> <?= escape($event['start_datetime'] ?? 'Date et heure non spécifiés') ?></p>
                                <p class="mb-2"><i class="fas fa-calendar text-primary me-2"></i> <?= escape($event['end_datetime'] ?? 'Date et heure non spécifiés') ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 text-center">
                                <button type="button" class="btn btn-primary show-details" data-bs-toggle="modal" data-bs-target="#eventDetailsModal<?= escape($event['id']) ?>">
                                    <i class="fas fa-info-circle me-2"></i>Détails
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="eventDetailsModal<?= escape($event['id']) ?>" tabindex="-1" aria-labelledby="eventDetailsModalLabel<?= escape($event['id']) ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="eventDetailsModalLabel<?= escape($event['id']) ?>">
                                        <i class="fas fa-calendar-alt me-2"></i>Détails de l'événement
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h3 id="eventName<?= escape($event['id']) ?>" class="text-primary mb-4">
                                        <?= escape($event['name'] ?? 'Sans nom') ?>
                                    </h3>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <p class="mb-3">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                <strong>Lieu:</strong>
                                                <span id="eventLocation<?= escape($event['id']) ?>">
                                                    <?= escape($event['location'] ?? 'Lieu non spécifié') ?>
                                                </span>
                                            </p>
                                            <p class="mb-3">
                                                <i class="fas fa-calendar text-primary me-2"></i>
                                                <strong>Début:</strong>
                                                <span id="start_datetime<?= escape($event['id']) ?>">
                                                    <?php
                                                    if (!empty($event['start_datetime'])) {
                                                        $date = new DateTime($event['start_datetime']);
                                                        echo escape($date->format('d/m/Y H:i'));
                                                    } else {
                                                        echo 'Date de début non spécifiée';
                                                    }
                                                    ?>
                                                </span>
                                            </p>
                                            <p class="mb-3">
                                                <i class="fas fa-calendar text-primary me-2"></i>
                                                <strong>Fin:</strong>
                                                <span id="end_datetime<?= escape($event['id']) ?>">
                                                    <?php
                                                    if (!empty($event['end_datetime'])) {
                                                        $date = new DateTime($event['end_datetime']);
                                                        echo escape($date->format('d/m/Y H:i'));
                                                    } else {
                                                        echo 'Date de fin non spécifiée';
                                                    }
                                                    ?>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-3">
                                                <i class="fas fa-users text-primary me-2"></i>
                                                <strong>Organisateur:</strong>
                                                <span id="eventOrganizer<?= escape($event['id']) ?>">
                                                    <?= escape($event['organizer'] ?? 'Organisateur non spécifié') ?>
                                                </span>
                                            </p>
                                            <p class="mb-3">
                                                <i class="fas fa-tag text-primary me-2"></i>
                                                <strong>Catégorie:</strong>
                                                <span id="eventCategory<?= escape($event['id']) ?>">
                                                    <?= escape($event['category'] ?? 'Catégorie non spécifiée') ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="description-section">
                                        <h4 class="text-primary mb-3">Description</h4>
                                        <div id="eventDescription<?= escape($event['id']) ?>" class="mb-4 p-3 bg-light rounded">
                                            <?= escape($event['description'] ?? 'Pas de description disponible') ?>
                                        </div>
                                    </div>

                                    <div id="eventAdditionalInfo<?= escape($event['id']) ?>">
                                        <?php if (!empty($event['participants'])): ?>
                                            <h4 class="text-primary mb-3">Participants</h4>
                                            <p class="mb-3">
                                                <i class="fas fa-users text-primary me-2"></i>
                                                <?= escape($event['participants']) ?> personnes inscrites
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($event['requirements'])): ?>
                                            <h4 class="text-primary mb-3">Prérequis</h4>
                                            <p class="mb-3">
                                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                                <?= escape($event['requirements']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Fermer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="addEventC.php" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-2"></i>Ajouter un événement
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
    <script src="../../assets/js/jquery-migrate-3.0.1.min.js"></script>
    <script src="../../assets/js/jquery.easing.1.3.js"></script>
    <script src="../../assets/js/jquery.waypoints.min.js"></script>
    <script src="../../assets/js/jquery.stellar.min.js"></script>
    <script src="../../assets/js/jquery.magnific-popup.min.js"></script>
    <script src="../../assets/js/jquery.fancybox.min.js"></script>
    <script src="../../assets/js/jquery.animateNumber.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/bootstrap-datepicker.js"></script>
    <script src="../../assets/js/aos.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/Event.js"></script>
    <script src="../../assets/js/validation.js"></script>

    <script>
    $(document).ready(function() {
        // Hide loader when page is loaded
        $('#ftco-loader').removeClass('show');

        // Initialize Owl Carousel
        $('.owl-carousel').owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            margin: 0,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            nav: false,
            autoplayHoverPause: false,
            dots: true,
        });

        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'slide'
        });

        // Initialize Stellar for parallax
        if (!(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
            $.stellar({
                horizontalScrolling: false,
                verticalOffset: 0,
                responsive: true
            });
        }

        // Initialize Magnific Popup
        $('.image-popup').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            mainClass: 'mfp-no-margins mfp-with-zoom',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1]
            },
            image: {
                verticalFit: true
            },
            zoom: {
                enabled: true,
                duration: 300
            }
        });

        // Initialize FancyBox
        $('[data-fancybox]').fancybox({
            protect: true
        });
    });
    </script>
</body>
</html>