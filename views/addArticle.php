<?php
// Démarrer la session pour stocker les messages
session_start();

// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration de la base de données
define('DB_DSN', 'mysql:host=localhost;dbname=givehope;charset=utf8');
define('DB_USER', 'root');
define('DB_PASS', '');

// Test de débogage
// echo "Début du script<br>"; // Commenté pour éviter l'affichage avant l'HTML

// Inclure les fichiers nécessaires avec les bons chemins
// Assurez-vous que ces chemins sont corrects par rapport à l'emplacement de ce fichier
require_once __DIR__ . '/../../../app/Controllers/ArticleController.php';
require_once __DIR__ . '/../../../app/Models/Article.php';
require_once __DIR__ . '/../../../config/config.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyage des données
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
    $publication_date = filter_input(INPUT_POST, 'publication_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    $created_at = date('Y-m-d H:i:s');

    // Validation
    $errors = [];

    if (empty($title)) $errors[] = "Le titre est requis";
    if (empty($content)) $errors[] = "Le contenu est requis";
    if (empty($category)) $errors[] = "La catégorie est requise";
    if (empty($author)) $errors[] = "L'auteur est requis";
    if (empty($publication_date)) $errors[] = "La date de publication est requise";
    if (empty($status)) $errors[] = "Le statut est requis";

    if (empty($errors)) {
        try {
            // Connexion à la base de données
            $pdo = new PDO("mysql:host=localhost;dbname=givehope", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparation et exécution de la requête
            $stmt = $pdo->prepare("INSERT INTO articles (title, content, category, author, publication_date, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $content, $category, $author, $publication_date, $status, $created_at]);

            if ($result) {
                $_SESSION['success'] = 'Article ajouté avec succès!';
                header('Location: articleList.php');
                exit;
            } else {
                $_SESSION['error'] = 'Une erreur est survenue lors de l\'ajout de l\'article.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur : " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ajouter un Article - GiveHope</title>

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

  <!-- Custom CSS -->
  <style>
    select.form-control {
      position: relative;
      z-index: 9999;
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    select.form-control option {
      background-color: #f0f0f0;
      color: #333;
      padding: 10px;
    }

    .error-message {
      font-size: 0.875rem;
      color: red;
      display: none;
    }

    /* Style pour le formulaire */
    .site-section {
      padding: 7em 0;
    }

    .featured-section {
      background: #fff;
      padding: 3em;
      border-radius: 10px;
      box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.2);
    }

    .form-group label {
      font-weight: 500;
      color: #333;
    }

    .form-control {
      border-radius: 5px;
      border: 1px solid #ddd;
      padding: 10px 15px;
    }

    .form-control:focus {
      border-color: #F96D00;
      box-shadow: 0 0 0 0.2rem rgba(249, 109, 0, 0.25);
    }

    textarea.form-control {
      resize: vertical;
    }

    .btn-white {
      background-color: #fff;
      border-color: #fff;
      color: #F96D00;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .btn-white:hover {
      background-color: #F96D00;
      border-color: #F96D00;
      color: #fff;
    }

    /* Loader */
    #ftco-loader {
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background: rgba(255, 255, 255, 0.9);
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
      visibility: visible;
      opacity: 1;
      transition: opacity 0.3s ease;
    }

    #ftco-loader.hide {
      opacity: 0;
      visibility: hidden;
    }

    .circular {
      animation: rotate 2s linear infinite;
      height: 100%;
      transform-origin: center center;
      width: 100%;
      position: absolute;
      top: 0;
      left: 0;
      margin: auto;
    }

    .path {
      stroke-dasharray: 1, 200;
      stroke-dashoffset: 0;
      animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
      stroke-linecap: round;
    }

    @keyframes rotate {
      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes dash {
      0% {
        stroke-dasharray: 1, 200;
        stroke-dashoffset: 0;
      }
      50% {
        stroke-dasharray: 89, 200;
        stroke-dashoffset: -35px;
      }
      100% {
        stroke-dasharray: 89, 200;
        stroke-dashoffset: -124px;
      }
    }

    @keyframes color {
      100%,
      0% {
        stroke: #F96D00;
      }
      40% {
        stroke: #F96D00;
      }
      66% {
        stroke: #F96D00;
      }
      80%,
      90% {
        stroke: #F96D00;
      }
    }

    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      border-radius: 4px;
    }
    .alert-success {
      color: #3c763d;
      background-color: #dff0d8;
      border-color: #d6e9c6;
    }
    .alert-danger {
      color: #a94442;
      background-color: #f2dede;
      border-color: #ebccd1;
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
          <li class="nav-item"><a href="Article.html" class="nav-link">Article</a></li>
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
              <h2 class="heading mb-5">Ajouter Article</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FORMULAIRE -->
  <div class="site-section bg-light">
    <div class="container">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
          <?php 
          echo $_SESSION['error'];
          unset($_SESSION['error']);
          ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?php 
          echo $_SESSION['success'];
          unset($_SESSION['success']);
          ?>
        </div>
      <?php endif; ?>

      <div class="featured-section overlay-color-2">
        <div class="row">
          <div class="col-md-6 mb-5 mb-md-0">
            <img src="../../../images/article.jpeg" alt="Image placeholder" class="img-fluid">
          </div>
          <div class="col-md-6 pl-md-5">
            <form id="article-form" method="post" action="addArticle.php">
              <div class="form-group">
                <label for="title">Titre de l'article</label>
                <input type="text" class="form-control py-2" id="title" name="title" placeholder="Entrez le titre de l'article" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                <span id="titleError" class="error-message"></span>
              </div>

              <div class="form-group">
                <label for="content">Contenu de l'article</label>
                <textarea class="form-control py-2" id="content" name="content" rows="10" placeholder="Écrivez le contenu de l'article ici..."><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                <span id="contentError" class="error-message"></span>
              </div>

              <div class="form-group">
                <label for="category">Catégorie</label>
                <select class="form-control py-2" id="category" name="category">
                  <option value="" disabled <?php echo !isset($_POST['category']) ? 'selected' : ''; ?>>Sélectionnez une catégorie</option>
                  <option value="Éducation" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Éducation') ? 'selected' : ''; ?>>Éducation</option>
                  <option value="Santé" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Santé') ? 'selected' : ''; ?>>Santé</option>
                  <option value="Environnement" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Environnement') ? 'selected' : ''; ?>>Environnement</option>
                  <option value="Droits de l'homme" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Droits de l\'homme') ? 'selected' : ''; ?>>Droits de l'homme</option>
                  <option value="Aide humanitaire" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Aide humanitaire') ? 'selected' : ''; ?>>Aide humanitaire</option>
                </select>
                <span id="categoryError" class="error-message"></span>
              </div>

              <div class="form-group">
                <label for="author">Auteur</label>
                <input type="text" class="form-control py-2" id="author" name="author" placeholder="Entrez le nom de l'auteur" value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>">
                <span id="authorError" class="error-message"></span>
              </div>

              <div class="form-group">
                <label for="publication_date">Date de publication</label>
                <input type="date" class="form-control py-2" id="publication_date" name="publication_date" value="<?php echo isset($_POST['publication_date']) ? htmlspecialchars($_POST['publication_date']) : ''; ?>">
                <span id="publicationDateError" class="error-message"></span>
              </div>

              <div class="form-group">
                <label for="status">Statut de l'article</label>
                <select class="form-control py-2" id="status" name="status">
                  <option value="" disabled <?php echo !isset($_POST['status']) ? 'selected' : ''; ?>>Sélectionnez un statut</option>
                  <option value="Brouillon" <?php echo (isset($_POST['status']) && $_POST['status'] === 'Brouillon') ? 'selected' : ''; ?>>Brouillon</option>
                  <option value="Publié" <?php echo (isset($_POST['status']) && $_POST['status'] === 'Publié') ? 'selected' : ''; ?>>Publié</option>
                  <option value="Archivé" <?php echo (isset($_POST['status']) && $_POST['status'] === 'Archivé') ? 'selected' : ''; ?>>Archivé</option>
                </select>
                <span id="statusError" class="error-message"></span>
              </div>

              <div class="form-group text-center">
                <input type="submit" class="btn btn-white px-5 py-2" value="Ajouter l'article">
              </div>
            </form>
          </div>
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
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3192.344830711709!2d10.19408171529176!3d36.8987750799483!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12fd35710630d399%3A0x528e78c7892172b!2sTechnopole%20El%20Ghazala!5e0!3m2!1sen!2stn!4v1648730707081!5m2!1sen!2stn" width="300" height="270" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <div class="col-md-4">
          <h3 class="heading-section border-bottom pb-2 mb-3">Suivez-nous</h3>
          <div class="social-icons d-flex gap-3 mb-4">
            <a href="#" class="text-white"><i class="fab fa-facebook fa-2x"></i></a>
            <a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
            <a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
            <a href="#" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
          </div>
          <form class="d-flex">
            <input type="email" class="form-control me-2" placeholder="Votre email">
            <button type="submit" class="btn btn-primary">OK</button>
          </form>
        </div>
      </div>
      <div class="row mt-4 pt-3 border-top">
        <div class="col-md-6">
          <p>© 2025 Give4You. Tous droits réservés.</p>
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

  <!-- Script de validation du formulaire -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Sélection du formulaire
      const articleForm = document.getElementById('article-form');
      
      // Sélection des champs
      const titleInput = document.getElementById('title');
      const contentInput = document.getElementById('content');
      const categorySelect = document.getElementById('category');
      const authorInput = document.getElementById('author');
      const publicationDateInput = document.getElementById('publication_date');
      const statusSelect = document.getElementById('status');
      
      // Définir la date minimale comme aujourd'hui pour le champ de date
      const today = new Date();
      const yyyy = today.getFullYear();
      let mm = today.getMonth() + 1;
      let dd = today.getDate();
      
      if (dd < 10) dd = '0' + dd;
      if (mm < 10) mm = '0' + mm;
      
      const formattedToday = yyyy + '-' + mm + '-' + dd;
      publicationDateInput.setAttribute('min', formattedToday);
      
      // Sélection des éléments d'erreur
      const titleError = document.getElementById('titleError');
      const contentError = document.getElementById('contentError');
      const categoryError = document.getElementById('categoryError');
      const authorError = document.getElementById('authorError');
      const publicationDateError = document.getElementById('publicationDateError');
      const statusError = document.getElementById('statusError');
      
      // Fonction pour valider le formulaire
      function validateForm() {
        let isValid = true;
        
        // Validation du titre
        if (!titleInput.value.trim()) {
          displayError(titleError, 'Le titre est obligatoire');
          isValid = false;
        } else if (titleInput.value.trim().length < 5) {
          displayError(titleError, 'Le titre doit contenir au moins 5 caractères');
          isValid = false;
        } else if (titleInput.value.trim().length > 100) {
          displayError(titleError, 'Le titre ne doit pas dépasser 100 caractères');
          isValid = false;
        }
        
        // Validation du contenu
        if (!contentInput.value.trim()) {
          displayError(contentError, 'Le contenu est obligatoire');
          isValid = false;
        } else if (contentInput.value.trim().length < 50) {
          displayError(contentError, 'Le contenu doit contenir au moins 50 caractères');
          isValid = false;
        }
        
        // Validation de la catégorie
        if (categorySelect.value === '' || categorySelect.selectedIndex === 0) {
          displayError(categoryError, 'Veuillez sélectionner une catégorie');
          isValid = false;
        }
        
        // Validation de l'auteur
        if (!authorInput.value.trim()) {
          displayError(authorError, 'Le nom de l\'auteur est obligatoire');
          isValid = false;
        } else if (!/^[a-zA-ZÀ-ÿ\s-]+$/.test(authorInput.value.trim())) {
          displayError(authorError, 'Le nom de l\'auteur ne doit contenir que des lettres, espaces et tirets');
          isValid = false;
        }
        
        // Validation de la date de publication
        if (!publicationDateInput.value) {
          displayError(publicationDateError, 'La date de publication est obligatoire');
          isValid = false;
        } else {
          const selectedDate = new Date(publicationDateInput.value);
          const today = new Date();
          today.setHours(0, 0, 0, 0);
          
          if (selectedDate < today) {
            displayError(publicationDateError, 'La date de publication ne peut pas être antérieure à aujourd\'hui');
            isValid = false;
          }
        }
        
        // Validation du statut
        if (statusSelect.value === '' || statusSelect.selectedIndex === 0) {
          displayError(statusError, 'Veuillez sélectionner un statut');
          isValid = false;
        }
        
        return isValid;
      }
      
      // Fonction pour afficher un message d'erreur
      function displayError(element, message) {
        element.textContent = message;
        element.style.display = 'block';
      }
      
      // Fonction pour réinitialiser tous les messages d'erreur
      function resetErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(function(element) {
          element.textContent = '';
          element.style.display = 'none';
        });
      }
      
      // Ajouter des événements de validation en temps réel
      titleInput.addEventListener('input', function() {
        titleError.style.display = 'none';
      });
      
      contentInput.addEventListener('input', function() {
        contentError.style.display = 'none';
      });
      
      categorySelect.addEventListener('change', function() {
        categoryError.style.display = 'none';
      });
      
      authorInput.addEventListener('input', function() {
        authorError.style.display = 'none';
      });
      
      publicationDateInput.addEventListener('input', function() {
        publicationDateError.style.display = 'none';
      });
      
      statusSelect.addEventListener('change', function() {
        statusError.style.display = 'none';
      });
      
      // Validation du formulaire avant soumission
      articleForm.addEventListener('submit', function(event) {
        resetErrors();
        if (!validateForm()) {
          event.preventDefault();
        }
      });
    });

    // Script pour masquer le loader une fois la page chargée
    window.addEventListener('load', function() {
      const loader = document.getElementById('ftco-loader');
      if (loader) {
        loader.classList.add('hide');
      }
    });
  </script>
</body>
</html>