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

            // Créer un objet Event en passant les 8 arguments attendus au constructeur
            $event = new Event(
                $eventName,
                $eventDescription,
                $startDatetime,
                $endDatetime,
                $eventLocation,
                $eventCategory,
                $eventOrganizer,
                $eventStatus
            );

            // Appel du contrôleur pour ajouter l'événement
            $result = $eventController->addEvent($event);

            if ($result) {
                $success = true;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   
  <style>
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
    animation: slideInLeft 0.5s ease-out;
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
            transition: 0.2s;
        }

        #sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        #sidebar ul li a i {
            width: 25px;
            text-align: center;
        }

    /* Form Section */
    .main-content {
      margin-left: 250px; /* Ensure the content isn't under the sidebar */
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f8f9fa;
    }

    .event-container {
      width: 100%;
      max-width: 800px;
      padding: 40px;
      box-sizing: border-box;
    }

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

    footer {
      background-color: rgb(25, 34, 74);
      color: white;
      padding: 20px 0;
      text-align: center;
      position: relative;
      bottom: 0;
      width: 100%;
    }

    footer a {
      color: white;
      text-decoration: none;
      margin: 0 10px;
    }

    footer a:hover {
      text-decoration: underline;
    }

    /* Responsiveness */
    @media (max-width: 1024px) {
      #sidebar {
        width: 200px;
      }

      .main-content {
        margin-left: 200px;
      }

      .event-container {
        padding: 20px;
      }
    }

    @media (max-width: 768px) {
      #sidebar {
        width: 100%;
        position: relative;
        height: auto;
      }

      .main-content {
        margin-left: 0;
      }

      .event-container {
        padding: 20px;
      }

      .form-container {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

    <!-- Sidebar -->
  <div id="sidebar">
    <ul>
      <li><a href="#">Dashboard</a></li>
      <li><a href="eventList.php">Liste des évènements</a></li>
      <li><a href="addEvent.php">ajouter un évènement</a></li>
     
    </ul>
  </div>
    <div class="container">
        <div class="text-center mt-5">
            <h2>Ajouter un événement</h2>
            <p class="text-muted">Remplissez le formulaire ci-dessous pour créer un nouvel événement</p>
        </div>

        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" id="eventForm" novalidate>

                <div class="form-group mb-3">
                    <label for="event_name" class="required-field"><strong>Nom de l'événement</strong></label>
                    <input type="text" class="form-control" id="event_name" name="event_name" value="<?php echo getFormValue('event_name'); ?>" required />
                </div>

                <div class="form-group mb-3">
                    <label for="event_description" class="required-field"><strong>Description</strong></label>
                    <textarea class="form-control" id="event_description" name="event_description" rows="5" required><?php echo getFormValue('event_description'); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="start_datetime" class="required-field"><strong>Date début</strong></label>
                            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" value="<?php echo getFormValue('start_datetime'); ?>" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="end_datetime" class="required-field"><strong>Date fin</strong></label>
                            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" value="<?php echo getFormValue('end_datetime'); ?>" required />
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="event_location" class="required-field"><strong>Lieu</strong></label>
                    <input type="text" class="form-control" id="event_location" name="event_location" value="<?php echo getFormValue('event_location'); ?>" required />
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="event_category" class="required-field"><strong>Catégorie</strong></label>
                            <select class="form-control" id="event_category" name="event_category" required>
                                <option value="" disabled <?php echo empty(getFormValue('event_category')) ? 'selected' : ''; ?>>Sélectionnez une catégorie</option>
                                <?php
                                $categories = ['Collecte de fonds', 'Sensibilisation', 'Bénévolat', 'Événement caritatif', 'Atelier ou formation'];
                                foreach ($categories as $category) {
                                    $selected = getFormValue('event_category') === $category ? 'selected' : '';
                                    echo "<option value=\"$category\" $selected>$category</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="event_status" class="required-field"><strong>Statut</strong></label>
                            <select class="form-control" id="event_status" name="event_status" required>
                                <option value="" disabled <?php echo empty(getFormValue('event_status')) ? 'selected' : ''; ?>>Sélectionnez un statut</option>
                                <?php
                                $statuses = ['Planifié', 'En cours', 'Terminé'];
                                foreach ($statuses as $status) {
                                    $selected = getFormValue('event_status') === $status ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="event_organizer" class="required-field"><strong>Organisateur</strong></label>
                    <input type="text" class="form-control" id="event_organizer" name="event_organizer" value="<?php echo getFormValue('event_organizer'); ?>" required />
                </div>

                <div class="text-center mt-4">
                    <a href="eventList.php" class="btn btn-secondary px-4 py-2 me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary px-5 py-2">Ajouter l'événement</button>
                </div>
            </form>
        </div>
    </div>

  <!-- Footer Section -->
<footer>
    <p>&copy; 2025 Give4You. Tous droits réservés.</p>
    <p>
      <a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a> | <a href="#">Conditions d'utilisation</a>
    </p>
  </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
    ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="../../assets/js/jquery.easing.1.3.js"></script>
    <script src="../../assets/js/jquery.waypoints.min.js"></script>
    <script src="../../assets/js/jquery.stellar.min.js"></script>
    <script src="../../assets/js/jquery.magnific-popup.min.js"></script>
    <script src="../../assets/js/bootstrap-datepicker.js"></script>
    <script src="../../assets/js/jquery.fancybox.min.js"></script>
    <script src="../../assets/js/aos.js"></script>
    <script src="../../assets/js/jquery.animateNumber.min.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/Event.js"></script>
    <script src="../../assets/js/validation.js"></script>

    <?php if ($success): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: 'L\'événement a été ajouté avec succès !',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'eventList.php';
            }
        });
    </script>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            html: '<?php echo $error; ?>',
            confirmButtonText: 'OK'
        });
    </script>
    <?php endif; ?>

    <script>
    // Validation côté client avec JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('eventForm');

        form.addEventListener('submit', function(event) {
            let isValid = true;
            const startDate = new Date(document.getElementById('start_datetime').value);
            const endDate = new Date(document.getElementById('end_datetime').value);

            // Vérification de la date
            if (endDate <= startDate) {
                event.preventDefault();
                isValid = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur de validation',
                    text: 'La date de fin doit être après la date de début',
                    confirmButtonText: 'OK'
                });
            }

            // Autres validations si nécessaire...

            return isValid;
        });
    });
    </script>
</body>
</html>