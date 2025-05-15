<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Give4You — Créer une Association</title>

  <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
  <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
  <link rel="stylesheet" href="assets/css/magnific-popup.css">
  <link rel="stylesheet" href="assets/css/aos.css">
  <link rel="stylesheet" href="assets/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="assets/css/jquery.timepicker.css">
  <link rel="stylesheet" href="assets/css/flaticon.css">
  <link rel="stylesheet" href="assets/css/icomoon.css">
  <link rel="stylesheet" href="assets/css/fancybox.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/style.css">

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
      <li><a href="index.php">Liste des Associations</a></li>
      <li><a href="#">Gestion des Membres</a></li>
      <li><a href="#">Paramètres</a></li>
      <li><a href="#">Rapports</a></li>
      <li><a href="#">Historique</a></li>
    </ul>
  </div>
  <!-- Form Section -->
  <div class="main-content">
    <div class="event-container">
      <div class="form-container">
        <form id="associationForm" method="POST" onsubmit="return validateForm()">
          <div class="form-group">
            <label for="name" class="required-field">Nom de l'association</label>
            <input type="text" class="form-control py-2" id="name" name="name" placeholder="Entrez le nom de l'association">
            <span class="text-danger error" id="error-name"></span>
          </div>

          <div class="form-group">
            <label for="email" class="required-field">Email de contact</label>
            <input type="email" class="form-control py-2" id="email" name="email" placeholder="Entrez l'email de contact">
            <span class="text-danger error" id="error-email"></span>
          </div>

          <div class="form-group">
            <label for="description" class="required-field">Description de l'association</label>
            <textarea id="description" name="description" cols="30" rows="3" class="form-control py-2" placeholder="Décrivez l'association"></textarea>
            <span class="text-danger error" id="error-description"></span>
          </div>

          <div class="form-group">
            <label for="address" class="required-field">Adresse du siège social</label>
            <input type="text" class="form-control py-2" id="address" name="address" placeholder="Entrez l'adresse du siège social">
            <span class="text-danger error" id="error-address"></span>
          </div>

          <div class="form-group">
            <label for="phone" class="required-field">Numéro de téléphone</label>
            <input type="text" class="form-control py-2" id="phone" name="phone" placeholder="Entrez le numéro de téléphone">
            <span class="text-danger error" id="error-phone"></span>
          </div>

          <div class="form-group">
            <label for="domain" class="required-field">Domaine d'activité</label>
            <input type="text" class="form-control py-2" id="domain" name="domain" placeholder="Entrez le domaine d'activité">
            <span class="text-danger error" id="error-domain"></span>
          </div>

          <div class="form-group">
            <label for="creation_date" class="required-field">Date de création</label>
            <input type="date" class="form-control py-2" id="creation_date" name="creation_date">
            <span class="text-danger error" id="error-creation_date"></span>
          </div>

          <div class="form-group">
            <label for="country" class="required-field">Pays d'activité</label>
            <select id="country" name="country" class="form-control py-2">
              <option value="">-- Sélectionnez un pays --</option>
              <optgroup label="Afrique du Nord">
                <option value="Maroc">Maroc</option>
                <option value="Algérie">Algérie</option>
                <option value="Tunisie">Tunisie</option>
                <option value="Égypte">Égypte</option>
                <option value="Libye">Libye</option>
              </optgroup>
            </select>
            <span class="text-danger error" id="error-country"></span>
          </div>

          <div class="form-group" style="text-align: center;">
            <input type="submit" class="btn btn-white px-5 py-2" value="Créer">
            <input type="button" class="btn btn-white px-5 py-2" value="Retour" onclick="window.location.href='index.php'">
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer Section -->
  <footer>
    <p>&copy; 2025 Give4You. Tous droits réservés.</p>
    <p>
      <a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a> | <a href="#">Conditions d'utilisation</a>
    </p>
  </footer>

  <script>
    function validateForm() {
      let isValid = true;

      // Reset error messages
      document.querySelectorAll('.error').forEach(function(error) {
        error.textContent = '';
      });

      // Check if all required fields are filled
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const description = document.getElementById('description').value.trim();
      const address = document.getElementById('address').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const domain = document.getElementById('domain').value.trim();
      const creation_date = document.getElementById('creation_date').value.trim();
      const country = document.getElementById('country').value.trim();

      // Name validation
      if (name === '') {
        document.getElementById('error-name').textContent = 'Le nom de l\'association est requis.';
        isValid = false;
      }

      // Email validation
      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      if (email === '') {
        document.getElementById('error-email').textContent = 'L\'email est requis.';
        isValid = false;
      } else if (!emailPattern.test(email)) {
        document.getElementById('error-email').textContent = 'Veuillez entrer un email valide.';
        isValid = false;
      }

      // Description validation
      if (description === '') {
        document.getElementById('error-description').textContent = 'La description est requise.';
        isValid = false;
      }

      // Address validation
      if (address === '') {
        document.getElementById('error-address').textContent = 'L\'adresse est requise.';
        isValid = false;
      }

      // Phone validation
      const phonePattern = /^[0-9]{10}$/;
      if (phone === '') {
        document.getElementById('error-phone').textContent = 'Le numéro de téléphone est requis.';
        isValid = false;
      } else if (!phonePattern.test(phone)) {
        document.getElementById('error-phone').textContent = 'Veuillez entrer un numéro de téléphone valide.';
        isValid = false;
      }

      // Domain validation
      if (domain === '') {
        document.getElementById('error-domain').textContent = 'Le domaine d\'activité est requis.';
        isValid = false;
      }

      // Creation date validation
      if (creation_date === '') {
        document.getElementById('error-creation_date').textContent = 'La date de création est requise.';
        isValid = false;
      }

      // Country validation
      if (country === '') {
        document.getElementById('error-country').textContent = 'Le pays est requis.';
        isValid = false;
      }

      return isValid;
    }
  </script>

</body>
</html>
