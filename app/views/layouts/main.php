<?php
// Emplacement: givehope-master/app/views/layouts/main.php

/**
 * Gabarit (Layout) Principal de l'Application GiveHope MVC.
 *
 * Variables attendues (passées via $data lors de l'appel à $this->view() depuis un contrôleur):
 * - $pageTitle (string) : Le titre de la page.
 * - $contentView (string) : Chemin relatif de la vue de contenu (ex: 'donation/form').
 * - $viewData (array) : Données spécifiques pour la vue de contenu.
 * Peut aussi contenir : ['message' => ['type' => 'success', 'text' => 'Message...']]
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' — GiveHope MVC' : 'GiveHope MVC'; ?></title>
    
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
    
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.php?controller=donation&action=index">GiveHope MVC</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
            <?php 
                $currentController = $_GET['controller'] ?? 'donation';
                $currentAction = $_GET['action'] ?? 'index';
                $isDonationLinkActive = ($currentController === 'donation'); 
            ?>
            <li class="nav-item <?php echo $isDonationLinkActive ? 'active' : ''; ?>">
                <a href="index.php?controller=donation&action=index" class="nav-link">Faire un Don</a>
            </li>
            </ul>
        </div>
      </div>
    </nav>

    <main role="main">
        <?php
        // Affichage des Messages Flash
        if (isset($viewData['message']) && is_array($viewData['message']) && !empty($viewData['message']['text']) && !empty($viewData['message']['type'])) {
            $message_type_class = 'alert-info';
            switch ($viewData['message']['type']) {
                case 'success': $message_type_class = 'alert-success'; break;
                case 'error':   $message_type_class = 'alert-danger';  break;
                case 'warning': $message_type_class = 'alert-warning'; break;
            }
            echo '<div class="container mt-4 mb-0"><div class="alert ' . $message_type_class . ' alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($viewData['message']['text'], ENT_QUOTES, 'UTF-8');
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            echo '</div></div>';
        }

        // Inclusion de la Vue de Contenu Spécifique
        if (isset($contentView) && !empty($contentView)) {
            // Utilisation de APPROOT pour un chemin plus fiable
            $contentViewPath = APPROOT . '/app/views/' . $contentView . '.php'; 
            if (file_exists($contentViewPath)) {
                if (isset($viewData) && is_array($viewData)) {
                    extract($viewData);
                }
                require $contentViewPath; // Changed from require_once to require for views if they might be included multiple times in complex layouts (though not the case here)
            } else {
                $errorMessage = 'ERREUR LAYOUT: Fichier de contenu introuvable : ' . htmlspecialchars($contentViewPath, ENT_QUOTES, 'UTF-8');
                error_log($errorMessage);
                echo '<div class="container mt-4"><p class="alert alert-danger">' . $errorMessage . '</p></div>';
            }
        } else {
            $errorMessage = 'ERREUR LAYOUT: Variable $contentView non définie.';
            error_log($errorMessage);
            echo '<div class="container mt-4"><p class="alert alert-warning">' . $errorMessage . '</p></div>';
        }
        ?>

        <div class="featured-section overlay-color-2" style="background-image: url('images/bg_2.jpg'); margin-top: 3rem;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-5 mb-md-0">
                        <img src="images/bg_2.jpg" alt="Devenez bénévole" class="img-fluid rounded shadow">
                    </div>
                    <div class="col-md-6 pl-md-5">
                        <div class="form-volunteer p-4 bg-light rounded shadow">
                            <h2 class="mb-4 text-center">Devenez bénévole aujourd'hui</h2>
                            <p class="text-center text-muted mb-4">Rejoignez notre équipe et aidez-nous.</p>
                            <form action="#" method="post" id="volunteerForm"> <div class="form-group">
                                    <label for="volunteer-name" class="sr-only">Votre Nom</label>
                                    <input type="text" class="form-control py-2" id="volunteer-name" name="volunteer-name" placeholder="Entrez votre nom complet">
                                    <span id="volunteer-name-error" class="text-danger small mt-1 d-block"></span>
                                </div>
                                <div class="form-group">
                                     <label for="volunteer-email" class="sr-only">Votre Email</label>
                                    <input type="email" class="form-control py-2" id="volunteer-email" name="volunteer-email" placeholder="Entrez votre adresse email">
                                     <span id="volunteer-email-error" class="text-danger small mt-1 d-block"></span>
                                </div>
                                <div class="form-group">
                                    <label for="volunteer-message" class="sr-only">Votre Message</label>
                                    <textarea name="volunteer-message" id="volunteer-message" cols="30" rows="4" class="form-control py-2" placeholder="Écrivez votre message..."></textarea>
                                    <span id="volunteer-message-error" class="text-danger small mt-1 d-block"></span>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 py-2">Envoyer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer mt-5 pt-5 pb-4 bg-dark text-light">
        <div class="container">
            <div class="row mb-5">
                 <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                     <h3 class="heading-section text-white mb-4">À propos de GiveHope</h3>
                     <p class="lead-sm text-muted">Plateforme de démonstration MVC.</p>
                 </div>
                 <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                     <h3 class="heading-section text-white mb-4">Liens rapides</h3>
                      <ul class="list-unstyled">
                         <li><a href="index.php?controller=donation&action=index" class="text-muted footer-link">Faire un Don</a></li>
                      </ul>
                 </div>
                 <div class="col-md-12 col-lg-4">
                     <div class="block-23">
                       <h3 class="heading-section text-white mb-4">Contact (Exemple)</h3>
                       <ul class="list-unstyled text-muted">
                         <li class="mb-2"><span class="icon icon-map-marker mr-2 text-primary"></span><span class="text">123 Rue Fictive, Ville</span></li>
                         <li class="mb-2"><a href="tel://+1234567890" class="text-muted footer-link"><span class="icon icon-phone mr-2 text-primary"></span><span class="text">+1 234 567 890</span></a></li>
                         <li class="mb-2"><a href="mailto:info@givehope.example" class="text-muted footer-link"><span class="icon icon-envelope mr-2 text-primary"></span><span class="text">info@givehope.example</span></a></li>
                       </ul>
                     </div>
                 </div>
             </div>
            <div class="row pt-4 border-top border-secondary">
                <div class="col-md-12 text-center">
                    <p class="small text-muted">
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> Tous droits réservés | GiveHope MVC
                        <br>Inspiré par <a href="https://colorlib.com" target="_blank" rel="nofollow noopener" class="text-secondary">Colorlib</a>.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
        </svg>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/main.js"></script>
    <script src="js/validation.js"></script> 
</body>
</html>