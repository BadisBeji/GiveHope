  <?php
  include 'controller/ProduitController.php';
  include_once 'model/produit.php';

  $controller = new ProduitController();
  $error = "";
  $produit = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
      if (
          isset($_POST['nom'], $_POST['prix'], $_POST['stock']) &&
          !empty($_POST['nom']) && !empty($_POST['prix']) && !empty($_POST['stock'])
      ) {
          $imagePath = null;

          if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
              $uploadDir = 'images_produits/';
              $imageName = basename($_FILES['image']['name']);
              $targetFile = $uploadDir . $imageName;

              if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                  $imagePath = $targetFile;
              } 
          } else {
              $existingProduct = $controller->getProduitById($_GET['id']);
              $imagePath = $existingProduct['image'];
          }

          $produit = new Produit(
              $_GET['id'],
              $_POST['nom'],
              $_POST['description'] ?? '',
              floatval($_POST['prix']),
              $_POST['categorie'] ?? 'Autre',
              intval($_POST['stock']),
              $imagePath
          );

          $controller->updateProduit($produit, $_GET['id']);
          header("Location: vendeur.php");
          exit;
      } 
  }
  ?>

  <!DOCTYPE html>
  <html lang="en">
    <head>
      <title>Modifier un produit</title>
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
          <a class="navbar-brand"style="color:black;" href="index.html">GiveHOPE</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
          </button>
          <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item"><a href="signup.html"style="color:black;" class="nav-link">Signup</a></li>
              <li class="nav-item"><a href="index.html"style="color:black;" class="nav-link">Home</a></li>
              <li class="nav-item"><a href="Association.html" style="color:black;"class="nav-link">Association</a></li>
              <li class="nav-item"><a href="donate.html" style="color:black;"class="nav-link">Donation</a></li>
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
  <br><br><br>
  <p style="max-width: 20%; margin-left: 70%;"><a href="vendeur.php" class="btn btn-primary btn-block">Retourner </a></p>

          
          <div class="container my-5">
              <h2 class="text-center">Modifier un Produit</h2>
              <?php
                $controller = new ProduitController();
                $produit = $controller->getProduitById($_GET['id']);
                ?>

              <form method="POST" enctype="multipart/form-data" onsubmit="return modifierProduit(event)"id="edit-product-form" action="modifierproduit.php?id=<?php echo $_GET['id']; ?>" class="p-4 bg-light rounded shadow">
                  
                  <!-- Nom du produit -->
                  <div class="form-group">
                      <label for="product-name">Nom du Produit :</label>
                      <input type="text"name="nom" value="<?php echo htmlspecialchars($produit['nom']); ?>" id="product-name" class="form-control" placeholder="Entrez le nom du produit" >
                  </div>
          
                  <!-- Prix -->
                  <div class="form-group">
                      <label for="product-price">Prix (TND) :</label>
                      <input type="text" value="<?php echo htmlspecialchars($produit['prix']); ?>"	name="prix" id="product-price" class="form-control" placeholder="Entrez le prix" >
                  </div>
          
                  <!-- Catégorie -->
                  <div class="form-group">
                      <label for="product-category">Catégorie :</label>
                      <select name="categorie" id="product-category" class="form-control" disabled>
                      <option value="Vêtements" <?php if ($produit['categorie'] == "Vêtements") echo "selected"; ?>>Vêtements Femmes</option>
                      <option value="Électronique" <?php if ($produit['categorie'] == "Électronique") echo "selected"; ?>>Vêtements Homme</option>
                      <option value="Maison" <?php if ($produit['categorie'] == "Maison") echo "selected"; ?>>Accessoires</option>
                      <option value="Autre" <?php if ($produit['categorie'] == "Autre") echo "selected"; ?>>Gadgets</option>
                  </select>
                  </div>
          
                  <div class="form-group">
                    <label for="product-stock">Stock :</label>
                    <input type="number" name="stock" id="product-stock" value="<?php echo htmlspecialchars($produit['stock']); ?>" class="form-control" placeholder="Ajoutez la quantité disponible en stock">
                    </div>
        

                  <!-- Description -->
                  <div class="form-group">
                      <label for="product-description">Description :</label>
                      <textarea id="product-description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($produit['description']); ?></textarea>
                  </div>
          
                  <!-- Image -->
                  <div class="form-group">
                        <?php if (!empty($produit['image'])): ?>
                    <div class="form-group">
                      <label>Image actuelle :</label><br>
                      <img src="<?php echo htmlspecialchars($produit['image']); ?>" alt="Image du produit" style="max-width: 200px; border: 1px solid #ccc;">
                    </div>
                  <?php endif; ?>
                      <label for="product-image">Image du Produit :</label>
                      <input type="file"	name="image" id="product-image" class="form-control-file">
                  </div>

          
                  <!-- Bouton de modification -->
                  <button type="submit" class="btn btn-primary btn-block" >Modifier le Produit</button>
              </form>
          </div>
          
          <script>
            function modifierProduit(event) {              
              let nomProduit = document.getElementById("product-name").value.trim();
              let prixInput = document.getElementById("product-price").value.trim();
              let imageInput = document.getElementById("product-image");
              let stockInput = document.getElementById("product-stock").value.trim();



              let stockInt = parseInt(stockInput);
              let prixFloat = parseFloat(prixInput);        
              if (isNaN(prixFloat) || prixFloat <= 0) {
          alert("Prix Invalide ! Veuillez entrer un nombre positif.");
          return false;
      }
              if ( isNaN(stockInt) ||stockInt <= 0) {
      alert("Veuillez entrer une quantité valide pour le stock (supérieure à 0).");
      return false;
  }
              if (!imageInput.files || imageInput.files.length === 0) {
          alert("Veuillez ajouter une image du produit !");
          return false;
      }

              alert("Produit modifié avec succès !");
              return true;
            }
          </script>
          
