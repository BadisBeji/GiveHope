<?php
include '../../controllers/EventController.php';

// Initialisation des variables
$error = "";
$success = false;
$event = null;

// Créer une instance du contrôleur
$eventController = new EventController();

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération et nettoyage des données du formulaire
    $eventName = filter_input(INPUT_POST, 'event_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $eventDescription = filter_input(INPUT_POST, 'event_description', FILTER_SANITIZE_SPECIAL_CHARS);
    $startDatetime = filter_input(INPUT_POST, 'start_datetime', FILTER_SANITIZE_SPECIAL_CHARS);
    $endDatetime = filter_input(INPUT_POST, 'end_datetime', FILTER_SANITIZE_SPECIAL_CHARS);
    $eventLocation = filter_input(INPUT_POST, 'event_location', FILTER_SANITIZE_SPECIAL_CHARS);
    $eventCategory = filter_input(INPUT_POST, 'event_category', FILTER_SANITIZE_SPECIAL_CHARS);
    $eventOrganizer = filter_input(INPUT_POST, 'event_organizer', FILTER_SANITIZE_SPECIAL_CHARS);
    $eventStatus = filter_input(INPUT_POST, 'event_status', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validation des données
    $errors = [];

    if (empty($eventName)) {
        $errors[] = "Le nom de l'événement est requis";
    }

    if (empty($eventDescription)) {
        $errors[] = "La description de l'événement est requise";
    }

    if (empty($startDatetime)) {
        $errors[] = "La date de début est requise";
    }

    if (empty($endDatetime)) {
        $errors[] = "La date de fin est requise";
    } else {
        // Vérifier que la date de fin est après la date de début
        $startDate = new DateTime($startDatetime);
        $endDate = new DateTime($endDatetime);

        if ($endDate <= $startDate) {
            $errors[] = "La date de fin doit être après la date de début";
        }
    }

    if (empty($eventLocation)) {
        $errors[] = "Le lieu de l'événement est requis";
    }

    if (empty($eventCategory)) {
        $errors[] = "La catégorie de l'événement est requise";
    }

    if (empty($eventOrganizer)) {
        $errors[] = "L'organisateur de l'événement est requis";
    }

    if (empty($eventStatus)) {
        $errors[] = "Le statut de l'événement est requis";
    }


    // Si aucune erreur, traiter les données
    if (empty($errors)) {
        try {
            // Création d'un objet Event à partir des données du formulaire
            require_once '../../models/Event.php'; // Assurez-vous que le chemin est correct

            // Créer un objet Event en passant les arguments attendus au constructeur
            $event = new Event(
                $eventName,
                $eventDescription,
                $startDatetime,
                $endDatetime,
                $eventLocation,
                $eventCategory,
                $eventOrganizer,
                $eventStatus,

            );

            // Appel du contrôleur pour ajouter l'événement
            $result = $eventController->addEvent($event);

            if ($result) {
                $success = true;
                // Rediriger vers la page display.php après un ajout réussi
                header("Location: display.php");
                exit();
            } else {
                $error = "Une erreur est survenue lors de l'ajout de l'événement";
            }
        } catch (Exception $e) {
            $error = "Erreur: " . $e->getMessage();
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

// Fonction pour conserver les valeurs soumises en cas d'erreur
function getFormValue($field) {
    return isset($_POST[$field]) ? htmlspecialchars($_POST[$field]) : '';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulaire d'événement - Give4You</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />

    <link
      href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />

    <link rel="stylesheet" href="../../assets/css/open-iconic-bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/animate.css" />
    <link rel="stylesheet" href="../../assets/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="../../assets/css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="../../assets/css/magnific-popup.css" />
    <link rel="stylesheet" href="../../assets/css/aos.css" />
    <link rel="stylesheet" href="../../assets/css/ionicons.min.css" />
    <link rel="stylesheet" href="../../assets/css/bootstrap-datepicker.css" />
    <link rel="stylesheet" href="../../assets/css/jquery.timepicker.css" />
    <link rel="stylesheet" href="../../assets/css/flaticon.css" />
    <link rel="stylesheet" href="../../assets/css/icomoon.css" />
    <link rel="stylesheet" href="../../assets/css/fancybox.min.css" />
    <link rel="stylesheet" href="../../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../../assets/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
        .event-container {
            display: flex;
            flex-wrap: wrap;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .event-info {
            flex: 1;
            padding-right: 30px;
            min-width: 300px;
        }
        .form-container {
            flex: 1;
            border: 1px solid #ccc;
            border-radius: 15px;
            padding: 20px;
            min-width: 300px;
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-control:focus {
            border-color: #4e9f3d;
            box-shadow: 0 0 0 0.25rem rgba(78, 159, 61, 0.25);
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        @media (max-width: 768px) {
            .event-container {
                flex-direction: column;
            }
            .event-info {
                padding-right: 0;
                padding-bottom: 30px;
            }
        }
    </style>
</head>
<body>

    <nav
      class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light"
      id="ftco-navbar"
    >
      <div class="container">
        <a class="navbar-brand" href="../../index.php">Give4You</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#ftco-nav"
          aria-controls="ftco-nav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a href="signup.html" class="nav-link">Inscription</a>
            </li>
            <li class="nav-item">
              <a href="../../index.php" class="nav-link">Accueil</a>
            </li>
            <li class="nav-item">
              <a href="indexFront.php" class="nav-link">Association</a>
            </li>
            <li class="nav-item">
              <a href="public" class="nav-link">Donation</a>
            </li>
            <li class="nav-item">
              <a href="views/front/addArticle.php" class="nav-link">Article</a>
            </li>
            <li class="nav-item active">
              <a href="views/front/display.php" class="nav-link">Événement</a>
            </li>
            <li class="nav-item">
              <a href="magasin.html" class="nav-link">E-Shop</a>
            </li>
            <li class="nav-item" id="my-events-nav">
              <a href="my-events.html" class="nav-link">Mes Événements</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="block-31" style="position: relative">
      <div class="owl-carousel loop-block-31">
        <div
          class="block-30 block-30-sm item"
          style="
            background-image: url('../../assets/images/fre.jpg');
            background-size: cover;
            background-position: center;
          "
          data-stellar-background-ratio="0.5"
        >
          <div class="container">
            <div class="row align-items-center justify-content-center">
              <div class="col-md-7 text-center">
                <h2 class="heading">Ajouter un événement</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="toast-container">
      <div
        class="toast align-items-center text-white bg-success border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        id="successToast"
      >
        <div class="d-flex">
          <div class="toast-body">
            <i class="fas fa-check-circle me-2"></i>
            <span id="successMessage">Événement ajouté avec succès!</span>
          </div>
          <button
            type="button"
            class="btn-close btn-close-white me-2 m-auto"
            data-bs-dismiss="toast"
            aria-label="Close"
          ></button>
        </div>
      </div>

      <div
        class="toast align-items-center text-white bg-danger border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        id="errorToast"
      >
        <div class="d-flex">
          <div class="toast-body">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span id="errorMessage"
              >Une erreur s'est produite. Veuillez réessayer.</span
            >
          </div>
          <button
            type="button"
            class="btn-close btn-close-white me-2 m-auto"
            data-bs-dismiss="toast"
            aria-label="Close"
          ></button>
        </div>
      </div>
    </div>

    <div class="event-container">
      <div class="event-info">
        <h2>Et si votre événement devenait un geste qui change des vies ?</h2>
        <p>
          Vous êtes une entreprise, une école, une association ou simplement une
          personne avec un grand cœur ? Offrez à votre projet une dimension
          solidaire en organisant un événement caritatif avec nous. Chaque
          action, petite ou grande, peut semer l'espoir là où il manque.
          Remplissez notre formulaire en quelques clics, et notre équipe
          passionnée vous aidera à transformer votre idée en une belle réalité.
          Ensemble, créons des moments qui rassemblent… et des sourires qui
          illuminent.
        </p>
        <h2 class="mb-4">Regardez cette vidéo</h2>
        <div class="embed-responsive embed-responsive-16by9">
          <video width="90%" height="auto" controls class="embed-responsive-item">
            <source src="../../assets/videos/charity.mp4" type="video/mp4" />
          </video>
        </div>
      </div>

      <div class="form-container">
        <form id="eventForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" novalidate>

          <div class="form-group mb-3">
            <label for="event_name" class="form-label required-field"><strong>Nom de l'événement</strong></label>
            <input
              type="text"
              class="form-control py-2"
              id="event_name"
              name="event_name"
              placeholder="Entrez le nom de l'événement"
              value="<?php echo getFormValue('event_name'); ?>"
              required
            />
            <div class="invalid-feedback">
              Veuillez entrer un nom pour votre événement.
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="event_description" class="form-label required-field"><strong>Description de l'événement</strong></label>
            <textarea
              class="form-control py-2"
              id="event_description"
              name="event_description"
              rows="5"
              placeholder="Décrivez l'événement ici..."
              required
            ><?php echo getFormValue('event_description'); ?></textarea>
            <div class="invalid-feedback">
              Veuillez décrire votre événement.
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="start_datetime" class="form-label required-field"><strong>Date et heure de début</strong></label>
            <input
              type="datetime-local"
              class="form-control py-2"
              id="start_datetime"
              name="start_datetime"
              value="<?php echo getFormValue('start_datetime'); ?>"
              required
            />
            <div class="invalid-feedback">
              Veuillez sélectionner une date et heure de début.
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="end_datetime" class="form-label required-field"><strong>Date et heure de fin</strong></label>
            <input
              type="datetime-local"
              class="form-control py-2"
              id="end_datetime"
              name="end_datetime"
              value="<?php echo getFormValue('end_datetime'); ?>"
              required
            />
            <div class="invalid-feedback">
              Veuillez sélectionner une date et heure de fin.
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="event_location" class="form-label required-field"><strong>Lieu de l'événement</strong></label>
            <input
              type="text"
              class="form-control py-2"
              id="event_location"
              name="event_location"
              placeholder="Entrez l'adresse ou le lieu de l'événement"
              value="<?php echo getFormValue('event_location'); ?>"
              required
            />
            <div class="invalid-feedback">
              Veuillez entrer le lieu de l'événement.
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="event_category" class="form-label required-field"><strong>Catégorie de l'événement</strong></label>
            <select
              class="form-control py-2"
              id="event_category"
              name="event_category"
              required
            >
              <option value="" disabled <?php echo empty(getFormValue('event_category')) ? 'selected' : ''; ?>>
                Sélectionnez une catégorie
              </option>
              <?php
              $categories = ['Collecte de fonds', 'Sensibilisation', 'Bénévolat', 'Événement caritatif', 'Atelier ou formation'];
              foreach ($categories as $category) {
                  $selected = getFormValue('event_category') === $category ? 'selected' : '';
                  echo "<option value=\"$category\" $selected>$category</option>";
              }
              ?>
            </select>
            <div class="invalid-feedback">
              Veuillez sélectionner une catégorie.
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="event_organizer" class="form-label required-field"><strong>Organisateur</strong></label>
            <input
              type="text"
              class="form-control py-2"
              id="event_organizer"
              name="event_organizer"
              placeholder="Entrez le nom de l'organisateur"
              value="<?php echo getFormValue('event_organizer'); ?>"
              required
            />
            <div class="invalid-feedback">
              Veuillez entrer le nom de l'organisateur.
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="event_status" class="form-label required-field"><strong>Statut</strong></label>
            <select class="form-control py-2" id="event_status" name="event_status" required>
                <option value="" disabled <?php echo empty(getFormValue('event_status')) ? 'selected' : ''; ?>>
                    Sélectionnez un statut
                </option>
                <option value="En attente" <?php echo getFormValue('event_status') === 'En attente' ? 'selected' : ''; ?>>En attente</option>

            </select>
            <div class="invalid-feedback">
                Veuillez sélectionner un statut.
            </div>
          </div>

          <div class="form-group text-center mt-4">
            <a href="display.php" class="btn btn-secondary px-4 py-2 me-2">
              <i class="fas fa-arrow-left me-2"></i>Annuler
            </a>

            <button type="submit" class="btn btn-primary px-5 py-2">
              <i class="fas fa-plus-circle me-2"></i>Ajouter l'événement
            </button>
            <button
              type="button"
              id="telechargerPDF"
              class="btn btn-outline-primary px-4 py-2 ms-2"
            >
              <i class="fas fa-file-pdf me-2"></i>Télécharger PDF
            </button>
          </div>
        </form>
      </div>
    </div>

    <div
      class="modal fade"
      id="pdfPreviewModal"
      tabindex="-1"
      aria-labelledby="pdfPreviewModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="pdfPreviewModalLabel">
              Prévisualisation du formulaire
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Fermer"
            ></button>
          </div>
          <div class="modal-body">
            <div id="pdfPreview" class="bg-white p-4 border rounded"></div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Fermer
            </button>
            <button type="button" id="confirmDownload" class="btn btn-primary">
              <i class="fas fa-download me-2"></i>Télécharger
            </button>
          </div>
        </div>
      </div>
    </div>

    <footer class="footer bg-dark text-white py-5 mt-5">
      <div class="container">
        <div class="row g-4">
          <div class="col-md-4">
            <h3 class="heading-section border-bottom pb-2 mb-3">
              À propos de nous
            </h3>
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
            <h3 class="heading-section border-bottom pb-2 mb-3">
              Contactez-nous
            </h3>
            <ul class="list-unstyled contact-list">
              <li class="mb-2">
                <i class="fas fa-envelope me-2 text-primary"></i>
                <a
                  href="mailto:give4you@contact.com"
                  class="text-white text-decoration-none hover-primary"
                  >give4you@contact.com</a
                >
              </li>
              <li class="mb-2">
                <i class="fas fa-phone me-2 text-primary"></i>
                <a
                  href="tel:+21653247404"
                  class="text-white text-decoration-none hover-primary"
                  >(+216) 53 247 404</a
                >
              </li>
              <li class="mb-2">
                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                <span
                  >B.P. 160, pôle Technologique, Z.I. Chotrana II, 2083</span
                >
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
                referrerpolicy="no-referrer-when-downgrade"
              ></iframe>
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
                <input
                  type="email"
                  class="form-control me-2"
                  placeholder="Votre email"
                />
                <button type="submit" class="btn btn-primary">OK</button>
              </form>
            </div>
          </div>
        </div>

        <div class="row mt-4 pt-3 border-top">
          <div class="col-md-6">
            <p class="small mb-0">
              © 2025 Give4You. Tous droits réservés.
            </p>
          </div>
          <div class="col-md-6">
            <ul class="list-inline text-md-end mb-0 small">
              <li class="list-inline-item">
                <a href="#" class="text-white text-decoration-none"
                  >Mentions légales</a
                >
              </li>
              <li class="list-inline-item">|</li>
              <li class="list-inline-item">
                <a href="#" class="text-white text-decoration-none"
                  >Politique de confidentialité</a
                >
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
        <circle
          class="path-bg"
          cx="24"
          cy="24"
          r="22"
          fill="none"
          stroke-width="4"
          stroke="#eeeeee"
        />
        <circle
          class="path"
          cx="24"
          cy="24"
          r="22"
          fill="none"
          stroke-width="4"
          stroke-miterlimit="10"
          stroke="#F96D00"
        />
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="../../assets/js/bootstrap-datepicker.js"></script>
    <script src="../../assets/js/aos.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/Event.js"></script>
    <script src="../../assets/js/validation.js"></script>

    <script>
    $(document).ready(function() {
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