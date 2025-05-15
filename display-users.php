<?php
// Connexion à la base de données avec PDO
$dsn = 'mysql:host=localhost;dbname=givehope;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les utilisateurs
    $stmt = $pdo->query("SELECT id, nom, prenom, cin, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>GiveHope — Website Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .btn-custom { background-color: #0f5e16; color: #fff; border-radius: 20px; padding: 6px 15px; font-size: 12px; font-weight: 600; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); cursor: pointer; }
    .btn-custom2 { background-color: #c46e0c; color: #fff; border-radius: 20px; padding: 6px 15px; font-size: 12px; font-weight: 600; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); cursor: pointer; }
    .btn-custom:hover { background-color: #ff4343; transform: translateY(-2px); }
    .btn-small { background-color: #ff0008; color: white; border-radius: 20px; padding: 6px 15px; font-size: 12px; font-weight: bold; border: none; transition: background-color 0.3s ease, transform 0.2s ease; }
    .btn-small:hover { background-color: #0056b3; transform: translateY(-2px); }
    body { font-family: 'Overpass', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
    html, body { height: 100%; margin: 0; display: flex; flex-direction: column; }
    .container { flex: 1; }
    footer.footer { margin-top: auto; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background-color: #F96D00; color: white; }
    tr:hover { background-color: #f1f1f1; }
    .d-flex { display: flex; justify-content: center; gap: 10px; }
  </style>
</head>
<body>

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

  <div class="block-31" style="position: relative;">
    <div class="owl-carousel loop-block-31 ">
      <div class="block-30 block-30-sm item" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-md-7 text-center">
              <h2 class="heading">Admin Dashboard</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <h2 class="text-center">Liste des utilisateurs</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>CIN</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($users && count($users) > 0) {
            foreach ($users as $row) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['nom']) . "</td>
                        <td>" . htmlspecialchars($row['prenom']) . "</td>
                        <td>" . htmlspecialchars($row['cin']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>
                          <div class='d-flex'>
                            <button class='btn-custom' onclick=\"window.location.href='update_user.php?id={$row['id']}'\">Modifier</button>
                            <button class='btn-custom btn-delete btn-small'>Supprimer</button>
                            <button class='btn-custom2'>Détail</button>
                          </div>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Aucun utilisateur trouvé</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3 class="heading-section">À propos de nous</h3>
                <p class="lead">
                    Chez Give4You, nous croyons en la puissance de la générosité pour changer des vies. Rejoignez-nous et faisons la différence.
                </p>
            </div>
            <div class="col-md-4">
                <h3 class="heading-section">Contactez-nous</h3>
                <ul class="list-unstyled">
                    <li><span class="icon icon-envelope"></span> give4you@contact.com</li>
                    <li><span class="icon icon-phone"></span> (+216) 53 247 404</li>
                    <li><span class="icon icon-map-marker"></span> B.P. 160, pôle Technologique, Z.I. Chotrana II, 2083</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h3 class="heading-section">Suivez-nous</h3>
                <ul class="list-unstyled">
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">LinkedIn</a></li>
                </ul>
            </div>
        </div>
    </div>
  </footer>

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$pdo = null; // Fermeture explicite (optionnelle)
?>
