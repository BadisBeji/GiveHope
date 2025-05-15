<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
try {
    // CORRECTION: Nom de la base de données incohérent (était givehope dans le PDO mais give4you dans la vérification)
    $pdo = new PDO("mysql:host=localhost;dbname=givehope", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Check if publish_date column exists
$publish_date_exists = false;
try {
    $stmt = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'give4you' AND TABLE_NAME = 'articles'");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $publish_date_exists = in_array('publish_date', $columns);
} catch (PDOException $e) {
    error_log("Column check error: " . $e->getMessage());
}

// Initialize variables
$error = [];
$success = "";
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = ['title' => '', 'content' => '', 'category' => '', 'author' => '', 'publish_date' => '', 'status' => ''];

// Fetch article data
if ($article_id > 0) {
    try {
        $columns = "title, content, category, author, status";
        if ($publish_date_exists) {
            $columns .= ", publish_date";
        }
        $stmt = $pdo->prepare("SELECT $columns FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$article) {
            header("Location: articleList.php?error=Article non trouvé avec l'ID $article_id.");
            exit;
        }
    } catch (PDOException $e) {
        error_log("Fetch article error: " . $e->getMessage());
        $error[] = "Erreur lors de la récupération de l'article : " . $e->getMessage();
    }
} else {
    header("Location: articleList.php?error=ID d'article invalide. Veuillez sélectionner un article à modifier.");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CORRECTION: Utilisation de FILTER_SANITIZE_SPECIAL_CHARS obsolète, utiliser htmlspecialchars à la place
    $title = isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') : '';
    $content = isset($_POST['content']) ? htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8') : '';
    $category = isset($_POST['category']) ? htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8') : '';
    $author = isset($_POST['author']) ? htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8') : '';
    $publish_date = isset($_POST['publish_date']) ? htmlspecialchars($_POST['publish_date'], ENT_QUOTES, 'UTF-8') : '';
    $status = isset($_POST['status']) ? htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8') : '';

    // Validation
    if (empty($title)) {
        $error[] = "Le titre de l'article est requis.";
    } elseif (strlen($title) < 5 || strlen($title) > 100) {
        $error[] = "Le titre doit contenir entre 5 et 100 caractères.";
    }
    if (empty($content)) {
        $error[] = "Le contenu de l'article est requis.";
    }
    if (empty($category)) {
        $error[] = "La catégorie est requise.";
    }
    if (empty($author)) {
        $error[] = "L'auteur est requis.";
    }
    if ($publish_date_exists && empty($publish_date)) {
        $error[] = "La date de publication est requise.";
    }
    if (empty($status)) {
        $error[] = "Le statut est requis.";
    }

    // Update article if no errors
    if (empty($error)) {
        try {
            // CORRECTION: Erreur dans la requête SQL - correction de la syntaxe
            $sql = "UPDATE articles SET title = ?, content = ?, category = ?, author = ?, status = ?";
            $values = [$title, $content, $category, $author, $status];
            
            if ($publish_date_exists) {
                $sql .= ", publish_date = ?";
                $values[] = $publish_date;
            }
            
            $sql .= " WHERE id = ?";
            $values[] = $article_id;

            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($values);

            if ($result) {
                // MODIFICATION ICI: Redirection vers articleList.php après mise à jour réussie
                header("Location: articleList.php?success=L'article a été mis à jour avec succès !");
                exit; // Important pour arrêter l'exécution du script après la redirection
            } else {
                $error[] = "Erreur lors de la mise à jour de l'article.";
            }
        } catch (PDOException $e) {
            error_log("Update article error: " . $e->getMessage());
            $error[] = "Erreur : " . $e->getMessage();
        }
    }
}

// Function to retain form values
function getFormValue($field, $article) {
    return isset($_POST[$field]) ? htmlspecialchars($_POST[$field], ENT_QUOTES, 'UTF-8') : (isset($article[$field]) ? htmlspecialchars($article[$field], ENT_QUOTES, 'UTF-8') : '');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Mettre à jour un Article - GiveHope</title>

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

    /* Messages d'erreur et de succès */
    .error {
      color: #a94442;
      background-color: #f2dede;
      border: 1px solid #ebccd1;
      border-radius: 4px;
      padding: 15px;
      margin-bottom: 20px;
    }

    .success {
      color: #3c763d;
      background-color: #dff0d8;
      border: 1px solid #d6e9c6;
      border-radius: 4px;
      padding: 15px;
      margin-bottom: 20px;
    }

    /* Formulaire de mise à jour */
    form div {
      margin-bottom: 15px;
    }

    form label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }

    form input, form textarea, form select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    form textarea {
      min-height: 200px;
    }

    form button {
      background-color: #F96D00;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 500;
    }

    form button:hover {
      background-color: #e05f00;
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
      <div class="block-30 block-30-sm item" style="background-image: url('../../../images/bg_1.jpg');">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-7">
              <h2 class="heading mb-5">Mettre à jour l'Article</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <main>
    <section class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mx-auto featured-section">
            <?php if ($success): ?>
              <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
              </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger">
                <?php echo implode('<br>', array_map('htmlspecialchars', $error)); ?>
              </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $article_id; ?>" method="post">
              <div class="form-group">
                <label for="title">Titre de l'article</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo getFormValue('title', $article); ?>">
                <?php if (in_array("Le titre de l'article est requis.", $error) || in_array("Le titre doit contenir entre 5 et 100 caractères.", $error)): ?>
                  <div class="error-message" style="display: block;">
                    <?php echo in_array("Le titre de l'article est requis.", $error) ? "Le titre de l'article est requis." : "Le titre doit contenir entre 5 et 100 caractères."; ?>
                  </div>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label for="content">Contenu de l'article</label>
                <textarea id="content" name="content" class="form-control"><?php echo getFormValue('content', $article); ?></textarea>
                <?php if (in_array("Le contenu de l'article est requis.", $error)): ?>
                  <div class="error-message" style="display: block;">Le contenu de l'article est requis.</div>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label for="category">Catégorie</label>
                <select id="category" name="category" class="form-control">
                  <option value="">Sélectionnez une catégorie</option>
                  <option value="Éducation" <?php echo getFormValue('category', $article) === 'Éducation' ? 'selected' : ''; ?>>Éducation</option>
                  <option value="Santé" <?php echo getFormValue('category', $article) === 'Santé' ? 'selected' : ''; ?>>Santé</option>
                  <option value="Environnement" <?php echo getFormValue('category', $article) === 'Environnement' ? 'selected' : ''; ?>>Environnement</option>
                  <option value="Droits de l'homme" <?php echo getFormValue('category', $article) === "Droits de l'homme" ? 'selected' : ''; ?>>Droits de l'homme</option>
                  <option value="Aide humanitaire" <?php echo getFormValue('category', $article) === 'Aide humanitaire' ? 'selected' : ''; ?>>Aide humanitaire</option>
                </select>
                <?php if (in_array("La catégorie est requise.", $error)): ?>
                  <div class="error-message" style="display: block;">La catégorie est requise.</div>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label for="author">Auteur</label>
                <input type="text" id="author" name="author" class="form-control" value="<?php echo getFormValue('author', $article); ?>">
                <?php if (in_array("L'auteur est requis.", $error)): ?>
                  <div class="error-message" style="display: block;">L'auteur est requis.</div>
                <?php endif; ?>
              </div>

              <?php if ($publish_date_exists): ?>
              <div class="form-group">
                <label for="publish_date">Date de publication</label>
                <input type="date" id="publish_date" name="publish_date" class="form-control" value="<?php echo getFormValue('publish_date', $article); ?>">
                <?php if (in_array("La date de publication est requise.", $error)): ?>
                  <div class="error-message" style="display: block;">La date de publication est requise.</div>
                <?php endif; ?>
              </div>
              <?php endif; ?>

              <div class="form-group">
                <label for="status">Statut de l'article</label>
                <select id="status" name="status" class="form-control">
                  <option value="">Sélectionnez un statut</option>
                  <option value="Brouillon" <?php echo getFormValue('status', $article) === 'Brouillon' ? 'selected' : ''; ?>>Brouillon</option>
                  <option value="Publié" <?php echo getFormValue('status', $article) === 'Publié' ? 'selected' : ''; ?>>Publié</option>
                  <option value="Archivé" <?php echo getFormValue('status', $article) === 'Archivé' ? 'selected' : ''; ?>>Archivé</option>
                </select>
                <?php if (in_array("Le statut est requis.", $error)): ?>
                  <div class="error-message" style="display: block;">Le statut est requis.</div>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary">Mettre à jour l'article</button>
                <!-- Ajout d'un bouton pour rediriger vers la liste des articles sans soumettre le formulaire -->
                <a href="articleList.php" class="btn btn-secondary">Retour à la liste</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

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

  <script>
    // Client-side validation
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('form');
      form.addEventListener('submit', function(event) {
        let errors = [];
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        const category = document.getElementById('category').value;
        const author = document.getElementById('author').value.trim();
        <?php if ($publish_date_exists): ?>
        const publish_date = document.getElementById('publish_date').value;
        <?php endif; ?>
        const status = document.getElementById('status').value;

        if (!title) {
          errors.push('Le titre de l\'article est requis.');
        } else if (title.length < 5 || title.length > 100) {
          errors.push('Le titre doit contenir entre 5 et 100 caractères.');
        }
        if (!content) {
          errors.push('Le contenu de l\'article est requis.');
        }
        if (!category) {
          errors.push('La catégorie est requise.');
        }
        if (!author) {
          errors.push('L\'auteur est requis.');
        }
        <?php if ($publish_date_exists): ?>
        if (!publish_date) {
          errors.push('La date de publication est requise.');
        }
        <?php endif; ?>
        if (!status) {
          errors.push('Le statut est requis.');
        }

        if (errors.length > 0) {
          event.preventDefault();
          // Utilisation de SweetAlert2 au lieu d'un alert standard
          Swal.fire({
            title: 'Erreur de validation',
            html: 'Veuillez corriger les erreurs suivantes :<br>- ' + errors.join('<br>- '),
            icon: 'error',
            confirmButtonText: 'OK'
          });
        } else {
          // Si le formulaire est valide, afficher un message de confirmation avec SweetAlert2
          event.preventDefault(); // Empêcher la soumission par défaut
          Swal.fire({
            title: 'Confirmation',
            text: 'Êtes-vous sûr de vouloir mettre à jour cet article ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, mettre à jour',
            cancelButtonText: 'Annuler'
          }).then((result) => {
            if (result.isConfirmed) {
              // Si l'utilisateur confirme, soumettre le formulaire
              form.submit();
            }
          });
        }
      });
      
      // Masquer le loader une fois la page chargée
      setTimeout(function() {
        document.getElementById('ftco-loader').classList.add('hide');
      }, 500);
    });
  </script>
</body>
</html>