<!DOCTYPE html>
<html lang="en">
  <head>
    <title>E-Shop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
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
            <li class="nav-item"><a href="magasin.php" class="nav-link">E-Shop</a></li>
           
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
              <h2 class="heading mb-5">Nos Produits</h2>  <h2 style="color:rgb(255, 255, 255); font-weight: bold;">Soutenez des personnes en besoin en achetant nos produits!</h2>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <br>



  <div style="display: flex; justify-content: flex-end; padding-right: 5%; ">
    <h4 style=" margin-right: 150px; ">Tous les bénéfices générés feront office de don.</h4>
    <p style="max-width: 20%; margin-right: 20px;" ><a href="commandes.html" class="btn btn-primary btn-block">Voir mes commandes</a></p> <br>
    <a href="panier.html">
    <button class="btn btn-primary">
      <img src="images/panier.png" height="30" width="30">
    </button></a>
    

    
  </div> 

  <div class="container my-4">
    <div class="product-container">
      
      <!-- Section des Filtres -->
      <div class="filters">
        <input type="text" id="searchBar" class="form-control mb-2" placeholder="Rechercher un produit..." onkeyup="filterProducts()">
        <button type="submit" class="btn btn-primary">Rechercher</button> 
  
        <div class="filter-options mt-3">
          <label><input type="radio" name="category" value="all" checked onclick="filterProducts()"> Tous</label>
          <label><input type="radio" name="category" value="homme" onclick="filterProducts()"> Vêtements Homme</label>
          <label><input type="radio" name="category" value="femme" onclick="filterProducts()"> Vêtements Femme</label>
          <label><input type="radio" name="category" value="accessoire" onclick="filterProducts()"> Accessoires</label>
          <label><input type="radio" name="category" value="gadget" onclick="filterProducts()"> Gadgets</label>
        </div>
      </div>
  
      <!-- Section des Produits -->
      <div class="products">
        <div class="site-section bg-light">
          <div class="container">
            <div class="row">
            <?php
              include 'controller/ProduitController.php';
              $controller = new ProduitController();
              $produits = $controller->getAllProduits();
              foreach ($produits as $produit):
              ?>
            <div class="col-md-4 mb-4">
                  <div class="post-entry">
                    <a href="#" class="mb-3 img-wrap">
                      <img src="<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="img-fluid" style="height: 150px; width:100%; object-fit: cover;">
                    </a>
                    <h3><a href="#"><?= htmlspecialchars($produit['nom']) ?></a></h3>
                    <p><?= nl2br(htmlspecialchars($produit['description'])) ?></p>
                    <p class="font-weight-bold"><?= htmlspecialchars($produit['prix']) ?> TND</p>
                    <p><a href="#" class="btn btn-primary">Ajouter au Panier</a></p>
                  </div>
                </div>
              <?php endforeach; ?>  
            </div>
          </div>
        </div>
      </div>
  
    </div>
  </div>
  <script>    window.onload = function() {
  setTimeout(() => {
      window.scrollTo({ top: 630, behavior: "smooth" });
  }, 450);
};</script>
  
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