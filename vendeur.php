<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord de vendeur</title>

    
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
    <link rel="stylesheet" href="css/fancybox.min.css">
    <link rel="stylesheet" href="css/shopcss.css">

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">

  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.html">Give4You</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
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
    <!-- FIN nav -->
   
  <div class="block-31" style="position: relative;">
    <div class="owl-carousel loop-block-31 ">
      <div class="block-30 block-30-sm item" style="background-image: url('images/headershop.jpg');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-7">
              <h2 class="heading mb-5">Vos produits</h2>  <h2 style="color:rgb(255, 255, 255); font-weight: bold;">Vendez vos produits tout en faisant des dons !</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<br>
<p style="max-width: 20%; margin-left: 40%;"><a href="magasin.php" class="btn btn-primary btn-block">Retour à la boutique</a></p>


<div class="dashboard-container">
    <br>
    <h2 class="dashboard-header" style="margin-left:10%">Tableau de Bord du Vendeur</h2>
<br>
    <!-- Statistiques -->
    <div style="max-width: 50%; margin-left: 10%; " class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <h5 style="color: white;margin-left: 10%;">Commandes en cours</h5>
                <h3 style="color: white;margin-left: 10%; ">5</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <h5 style="color: white;margin-left: 10%;">Commandes Livrées</h5>
                <h3 style="color: white;margin-left: 10%;">20</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <h5 style="color: white;margin-left: 10%;">Commandes Annulées</h5>
                <h3 style="color: white;margin-left: 10%;">3</h3>
            </div>
        </div>
    </div>
    <br>

 <!-- Liste des commandes -->
    <div style="margin-left: 10%; margin-right:10%;" class="table-container">
        <h4>Dernières Commandes</h4>
        <table class="table table-striped">
            <thead class="thead-light">
                <tr>
                    <th>ID Commande</th>
                    <th>Produit</th>
                    <th>Date</th>
                    <th>Montant Total</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#12345</td>
                    <td>Produit 2</td>
                    <td>25/02/2024</td>
                    <td>120 TND</td>
                    <td><span class="badge badge-warning">En cours</span></td>

                </tr>
                <tr>
                    <td>#12346</td>
                    <td>Produit 8</td>
                    <td>20/02/2024</td>
                    <td>80 TND</td>
                    <td><span class="badge badge-success">Livrée</span></td>
                 
                </tr>
                <tr>
                    <td>#12347</td>
                    <td>Produit 1</td>
                    <td>18/02/2024</td>
                    <td>150 TND</td>
                    <td><span class="badge badge-danger">Annulée</span></td>
         
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Gestion des Produits -->
    <div  style="margin-left: 10%; margin-right:10%;" class="table-container">
        <h4>Gestion des Produits</h4>
        <p style="max-width: 20%; margin-right: 20px;" ><a href="ajoutproduit.php" class="btn btn-primary btn-block">Ajouter un produit</a></p> <br>
        <table  class="table table-striped mt-3">
            <thead class="thead-light">
                <tr>
                    <th>ID Produit</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>    
             <?php
                include 'Controller/ProduitController.php';
                $controller = new ProduitController();
                $produits = $controller->getAllProduits();
                ?>

                <tbody>
                <?php foreach ($produits as $produit): ?>
                    <tr>
                        <td><?= htmlspecialchars($produit['id']) ?></td>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td><?= htmlspecialchars($produit['prix']) ?> TND</td>
                        <td><?= htmlspecialchars($produit['stock']) ?></td>
                        <td>
                            <a href="modifierproduit.php?id=<?= $produit['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="Controller/ProduitController.php?action=deleteProduit&id=<?= $produit['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
        </table>
    </div>


<br>
<footer class="footer">
  <div class="container">
      <div class="row">
          <!-- Section À propos de nous -->
          <div class="col-md-4">
              <h3 class="heading-section">À propos de nous</h3>
              <p class="lead">
                  Chez Give4You, nous croyons en la puissance de la générosité pour changer des vies. Notre mission est de venir en aide aux personnes dans le besoin, de soutenir les communautés vulnérables et de promouvoir un monde plus solidaire. Chaque don, chaque geste de bienveillance compte. Rejoignez-nous dans cette noble cause et ensemble, faisons la différence.
              </p>
          </div>

          <!-- Section Contact -->
          <div class="col-md-4">
              <h3 class="heading-section">Contactez-nous</h3>
              <ul class="list-unstyled">
                  <li><span class="icon icon-envelope"></span> give4you@contact.com</li>
                  <li><span class="icon icon-phone"></span> (+216) 53 247 404</li>
                  <li><span class="icon icon-map-marker"></span> B.P. 160, pôle Technologique, Z.I. Chotrana II, 2083</li>
              </ul>
          </div>

          <!-- Section Réseaux sociaux -->
          <div class="col-md-4">
              <h3 class="heading-section">Suivez-nous</h3>
              <ul class="list-unstyled">
                  <li><a href="#"><span class="icon icon-facebook"></span> Facebook</a></li>
                  <li><a href="#"><span class="icon icon-twitter"></span> Twitter</a></li>
                  <li><a href="#"><span class="icon icon-instagram"></span> Instagram</a></li>
                  <li><a href="#"><span class="icon icon-linkedin"></span> LinkedIn</a></li>
              </ul>
          </div>
      </div>

      <!-- Copyright -->
      <div class="row pt-5">
          <div class="col-md-12 text-center">
              <p>
                  &copy; <script>document.write(new Date().getFullYear());</script> Give4You. Tous droits réservés.
              </p>
          </div>
      </div>
  </div>
</footer>

<!-- loader -->
<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

<script src="js/jquery.min.js"></script>
<script src="js/jquery-migrate-3.0.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/jquery.fancybox.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/jquery.animateNumber.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
<script src="js/google-map.js"></script>
<script src="js/main.js"></script>
<script src="js/contact.js"></script>
<script src="js/validation.js"></script>
<script src="js/Article.js"></script>
<script src="js/Association.js"></script>
<script src="js/Event.js"></script>
</body>
</html>