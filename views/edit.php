<div class="form-container">
  <h2>Modifier une Association</h2>

  <?php if (!empty($errorMessage)) : ?>
    <div class="error-message" style="color: red; font-weight: bold;">
      <?= htmlspecialchars($errorMessage) ?>
    </div>
  <?php endif; ?>

  <form id="editForm" method="POST" onsubmit="return validateForm()">
    <div class="form-group">
      <label for="name">Nom :</label>
      <input type="text" id="name" name="name" value="<?= htmlspecialchars($assoc['name']) ?>" >
      <div class="error" id="error-name"></div>
    </div>

    <div class="form-group">
      <label for="email">Email :</label>
      <input type="text" id="email" name="email" value="<?= htmlspecialchars($assoc['email']) ?>">
      <div class="error" id="error-email"></div>
    </div>


    <div class="form-group">
      <label for="description">Description :</label>
      <textarea id="description" name="description" ><?= htmlspecialchars($assoc['description']) ?></textarea>
      <div class="error" id="error-description"></div>
    </div>

    <div class="form-group">
      <label for="address">Adresse :</label>
      <input type="text" id="address" name="address"  value="<?= htmlspecialchars($assoc['address']) ?>" >
      <div class="error" id="error-address"></div>
    </div>

    <div class="form-group">
      <label for="phone">Téléphone :</label>
      <input type="number" id="phone" name="phone" value="<?= htmlspecialchars($assoc['phone']) ?>" >
      <div class="error" id="error-phone"></div>
    </div>

    <div class="form-group">
      <label for="domain">Domaine :</label>
      <input type="text" id="domain" name="domain" value="<?= htmlspecialchars($assoc['domain']) ?>" >
      <div class="error" id="error-domain"></div>
    </div>

    <div class="form-group">
      <label for="creation_date">Date de création :</label>
      <input type="date" id="creation_date" name="creation_date" value="<?= htmlspecialchars($assoc['creation_date']) ?>" >
      <div class="error" id="error-date"></div>
    </div>

    <div class="form-group">
      <label for="country">Pays :</label>
      <select id="country" name="country" >
        <option value="">-- Sélectionnez un pays --</option>
        <?php
          $regions = [
            "Afrique du Nord" => ["Maroc", "Algérie", "Tunisie", "Égypte", "Libye"],
            "Afrique de l'Ouest" => ["Sénégal", "Cameroun", "Gabon", "Guinée", "Côte d'Ivoire", "Nigeria"],
            "Afrique Centrale" => ["Congo", "République démocratique du Congo", "Angola", "Burkina Faso", "Tchad"],
            "Afrique de l'Est" => ["Éthiopie", "Kenya", "Tanzanie", "Ouganda", "Rwanda", "Burundi"],
            "Afrique Australe" => ["Afrique du Sud", "Zimbabwe", "Mozambique", "Namibie", "Botswana"],
            "Asie" => ["Chine", "Inde", "Pakistan", "Indonésie", "Malaisie"],
            "Asie du Sud" => ["Indonésie", "Indonésie", "Indonésie", "Indonésie", "Indonésie"],
            "Asie Centrale" => ["Indonésie", "Indonésie", "Indonésie", "Indonésie", "Indonésie"],
            "Asie de l'Est" => ["Indonésie", "Indonésie", "Indonésie", "Indonésie", "Indonésie"],
            "Asie de l'Ouest" => ["Indonésie", "Indonésie", "Indonésie", "Indonésie", "Indonésie"],
            "Europe" => ["Espagne", "Italie", "Pologne", "Royaume-Uni", "Allemagne"],
            "Europe de l'Est" => ["Italie", "Pologne", "Royaume-Uni", "Allemagne"],
            "Europe de l'Ouest" => ["Espagne", "Italie", "Pologne", "Royaume-Uni", "Allemagne"],
            "Europe Centrale" => ["Italie", "Pologne", "Royaume-Uni", "Allemagne"],
            "Europe Australe" => ["Italie", "Pologne", "Royaume-Uni", "Allemagne"],
            "Oceania" => ["Nouvelle-Zelande", "Pologne", "Royaume-Uni", "Allemagne"],
            "Oceania de l'Est" => ["Nouvelle-Zelande", "Pologne", "Royaume-Uni", "Allemagne"],
            "Oceania de l'Ouest" => ["Nouvelle-Zelande", "Pologne", "Royaume-Uni", "Allemagne"],
            "Oceania Centrale" => ["Nouvelle-Zelande", "Pologne", "Royaume-Uni", "Allemagne"],
            "Oceania Australe" => ["Nouvelle-Zelande", "Pologne", "Royaume-Uni", "Allemagne"]
          ];

          foreach ($regions as $region => $countries) {
            echo "<optgroup label=\"$region\">";
            foreach ($countries as $country) {
              $selected = ($assoc['country'] === $country) ? "selected" : "";
              echo "<option value=\"$country\" $selected>$country</option>";
            }
            echo "</optgroup>";
          }
        ?>
      </select>
      <div class="error" id="error-country"></div>
    </div>

    <button type="submit">Modifier</button>
    <button type="return"><a href="index.php">Retour</a> </button>
  </form>
</div>

<!-- Le style reste inchangé -->
<style>
  body {
    background: linear-gradient(135deg,rgb(219, 61, 12),rgb(174, 230, 5));
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
  }

  .form-container {
    background-color: #ffffff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
  }

  .form-container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
    font-weight: 600;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 6px;
    color: #34495e;
  }

  .form-group input,
  .form-group textarea,
  .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    background-color: #f9f9f9;
    transition: border-color 0.3s;
  }

  .form-group input:focus,
  .form-group textarea:focus,
  .form-group select:focus {
    border-color: #3498db;
    outline: none;
  }

  button[type="submit"] {
    background-color: #3498db;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
  }

  button[type="submit"]:hover {
    background-color: #2980b9;
  }

  .error {
    color: #e74c3c;
    font-size: 14px;
    margin-top: 5px;
  }
  button[type="return"] {
      background-color:rgb(233, 221, 0);
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 13px;
      cursor: pointer;
      transition: background-color 0.3s;
      width: 100%;
    }
    button[type="return"]:hover {
      background-color: #2980b9;
    }
</style>

<!-- JS de validation : inchangé -->
<script>
  function validateForm() {
    let isValid = true;
    document.querySelectorAll(".error").forEach(e => e.textContent = "");

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const description = document.getElementById("description").value.trim();
    const address = document.getElementById("address").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const domain = document.getElementById("domain").value.trim();
    const creationDate = document.getElementById("creation_date").value;
    const country = document.getElementById("country").value.trim();

    if (name === "") {
      document.getElementById("error-name").textContent = "Le nom est requis.";
      isValid = false;
    }

    
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    document.getElementById("error-email").textContent = "Veuillez entrer une adresse email valide.";
    isValid = false;
  }

          


    if (description.length < 10) {
      document.getElementById("error-description").textContent = "La description doit contenir au moins 10 caractères.";
      isValid = false;
    }

    if (address === "") {
      document.getElementById("error-address").textContent = "L'adresse est requise.";
      isValid = false;
    }

    if (isNaN(phone) || phone.length < 8 || phone.length > 12) {
      document.getElementById("error-phone").textContent = "Le téléphone doit contenir entre 8 et 12 chiffres.";
      isValid = false;
    }

    if (domain.length < 3) {
      document.getElementById("error-domain").textContent = "Le domaine doit contenir au moins 3 caractères.";
      isValid = false;
    }

    if (creationDate === "") {
      document.getElementById("error-date").textContent = "La date de création est requise.";
      isValid = false;
    }

    if (country === "") {
      document.getElementById("error-country").textContent = "Le pays est requis.";
      isValid = false;
    }

    return isValid;
  }
</script>
