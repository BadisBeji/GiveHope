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
        <a class="navbar-brand" style="color:black;" href="index.html">GiveHope</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a href="signup.html" style="color:black;" class="nav-link">Signup</a></li>
            <li class="nav-item"><a href="index.html"style="color:black;"  class="nav-link">Home</a></li>
            <li class="nav-item"><a href="Association.html" style="color:black;" class="nav-link">Association</a></li>
            <li class="nav-item"><a href="donate.html"style="color:black;"  class="nav-link">Donation</a></li>
            <li class="nav-item"><a href="Article.html"style="color:black;"  class="nav-link">Article</a></li>
            <li class="nav-item"><a href="Event.html" style="color:black;" class="nav-link">Event</a></li>
            <li class="nav-item"><a href="magasin.php" style="color:black;" class="nav-link">E-Shop</a></li>
           
          </ul>
        </div>
      </div>
    </nav>
    <!-- FIN nav -->
   <br>
   <br> 
   <br> 

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
                    <th>Image</th>
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
                        <td><img src="<?php echo htmlspecialchars($produit['image']); ?>" alt="Image du produit" style="max-width: 100px; border: 1px solid #ccc;">
                </td>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td><?= htmlspecialchars($produit['prix']) ?> TND</td>
                        <td><?= htmlspecialchars($produit['stock']) ?></td>
                        <td>
                            <a href="modifierproduit.php?id=<?= $produit['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="Controller/ProduitController.php?action=deleteProduit&id=<?= $produit['id'] ?>" style="color:white;" class="btn btn-danger btn-sm"  onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
        </table>
    </div>
    <br> 
    <br> 