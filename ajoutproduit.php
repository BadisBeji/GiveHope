<?php
include 'controller/ProduitController.php';
include_once 'model/produit.php';

$error = "";
$produit = null;
$controller = new ProduitController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['nom'], $_POST['prix'], $_POST['categorie'], $_POST['stock']) &&
        isset($_FILES['image']) && $_FILES['image']['error'] === 0
    ) {
        $uploadDir = 'images_produits/';
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $produit = new Produit(
                null,
                $_POST['nom'],
                $_POST['description'] ?? '',
                floatval($_POST['prix']),
                $_POST['categorie'],
                intval($_POST['stock']),
                $targetFile
            );

            $controller->addProduit($produit);

        } 
    } 
}
?>

<!DOCTYPE html>
  <html lang="en">
    <head>
      <title>Ajout produit</title>
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
      
      <body>
        <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
          <div class="container">
            <a class="navbar-brand" style="color:black;" href="index.html">Give4You</a>
            <button class="navbar-toggler" style="color:black;" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="signup.html" style="color:black;" class="nav-link">Signup</a></li>
                <li class="nav-item"><a href="index.html" style="color:black;"class="nav-link">Home</a></li>
                <li class="nav-item"><a href="Association.html" style="color:black;"class="nav-link">Association</a></li>
                <li class="nav-item"><a href="donate.html"style="color:black;" class="nav-link">Donation</a></li>
                <li class="nav-item"><a href="Article.html" style="color:black;"class="nav-link">Article</a></li>
                <li class="nav-item"><a href="Event.html" style="color:black;"class="nav-link">Event</a></li>
                <li class="nav-item"><a href="magasin.php" style="color:black;"class="nav-link">E-Shop</a></li>
              
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
   <br>
    <br>
     <br>
      <br>
  <p style="max-width: 20%; margin-left: 70%;"><a href="vendeur.php" class="btn btn-primary btn-block">Retourner </a></p>

  <div class="container my-5">
      <h2 class="text-center">Ajouter un Nouveau Produit</h2>   <br>
        <form method="POST" action="" id="add-product-form" onsubmit="return validerprix()" enctype="multipart/form-data">
          
          <!-- Nom du produit -->
          <div class="form-group">
              <label for="product-name">Nom du Produit :</label>
              <input type="text" id="product-name" name="nom"class="form-control" placeholder="Entrez le nom du produit" >
          </div>

          <!-- Prix -->
          <div class="form-group">
              <label for="product-price">Prix (TND) :</label>
              <input type="text" id="product-price" name="prix" class="form-control" placeholder="Entrez le prix" >
          </div>

          <!-- Catégorie -->
          <div class="form-group">
              <label for="product-category">Catégorie :</label>
              <select id="product-category" class="form-control" 	name="categorie">
                  <option value="Vetements Femmes">Vêtements Femmes</option>
                  <option value="Vetements Homme">Vêtements Homme</option>
                  <option value="Accessoires">Accessoires</option>
                  <option value="Autre">Autre</option>
              </select>
          </div>

          <div class="form-group">
            <label for="product-stock">Stock :</label>
            <input type="text" 	name="stock" id="product-stock" class="form-control"  placeholder="Ajoutez la quantité disponible en stock"></textarea>
        </div>

          <!-- Description -->
          <div class="form-group">
              <label for="product-description">Description :</label>
              <textarea id="product-description" 	name="description"class="form-control" rows="3" placeholder="Ajoutez une description du produit"></textarea>
          </div>

          <!-- Image -->
          <div class="form-group">
              <label for="product-image">Image du Produit :</label>
              <input type="file" id="product-image" name="image" class="form-control-file">
          </div>

          <!-- Bouton d'ajout -->
        <button type="submit" class="btn btn-primary btn-block"  >Ajouter le Produit</button>
      </form>
  </div>
<script>
function validerprix() {
  let prixInput = document.getElementById("product-price").value.trim();
  let prixFloat = parseFloat(prixInput);
  let nomProduit = document.getElementById("product-name").value.trim();
  let imageInput = document.getElementById("product-image");
  let stockInput = document.getElementById("product-stock").value.trim();
  let stockInt = parseInt(stockInput);
  if (!nomProduit)
   {alert("Veuillez entrer un nom !");
  return false;
  }
  if (isNaN(prixFloat) || prixFloat <= 0) {
    alert("Prix invalide !");
    return false;}
    if (isNaN(stockInt) || stockInt <= 0) {
      alert("Stock invalide !");
      return false;}
      if (!imageInput.files || imageInput.files.length === 0) {
        alert("Veuillez ajouter une image !");
        return false;
      }
        alert("ajout avec succes !");
        return true;}

            </script>

