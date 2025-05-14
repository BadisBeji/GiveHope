<?php
// Emplacement: givehope-master/app/views/donation/form.php

/**
 * Vue pour le formulaire de création de don.
 * Cette vue est chargée par le DonationController, action index().
 * Elle reçoit les données suivantes via le tableau $viewData (extrait par layouts/main.php) :
 * - $pageTitle (string) : Titre de la page (utilisé par le layout).
 * - $form_errors (array) : Tableau associatif contenant les messages d'erreur de validation
 * pour chaque champ du formulaire (clé = nom du champ, valeur = message d'erreur).
 * Ex: ['donor-name' => 'Le nom est requis.']. Vide si pas d'erreurs.
 * - $form_data (array) : Tableau associatif contenant les données soumises précédemment
 * par l'utilisateur (en cas d'erreur de validation et de redirection).
 * Utilisé pour pré-remplir le formulaire. Clés correspondent aux attributs 'name' des inputs.
 * Ex: ['donor-name' => 'John Doe', 'donation-amount' => '50']. Vide la première fois.
 * - $latestDonations (array) : Tableau de tableaux associatifs, chaque sous-tableau représentant un don récent.
 * Utilisé pour afficher la section "Derniers dons".
 * Ex: [['donor_name' => 'Jane Doe', 'amount' => 100, 'cause' => 'education', 'donation_date' => '2024-05-10...'], ...]
 */

// --- Pré-remplissage des champs du formulaire et gestion des erreurs ---

// Récupérer les valeurs des champs depuis $form_data (données soumises en cas d'erreur précédente).
// Utiliser htmlspecialchars() pour se prémunir contre les attaques XSS si ces données sont réaffichées.
$donor_name_val = isset($form_data['donor-name']) ? htmlspecialchars($form_data['donor-name'], ENT_QUOTES, 'UTF-8') : '';
$donor_email_val = isset($form_data['donor-email']) ? htmlspecialchars($form_data['donor-email'], ENT_QUOTES, 'UTF-8') : '';
$donation_amount_val = isset($form_data['donation-amount']) ? htmlspecialchars($form_data['donation-amount'], ENT_QUOTES, 'UTF-8') : '';
$donation_cause_val = isset($form_data['donation-cause']) ? $form_data['donation-cause'] : ''; // Pas besoin de htmlspecialchars pour la valeur d'un <select> qui est fixe
// Pour la checkbox 'accept-terms', vérifier si elle était cochée lors de la soumission précédente.
$accept_terms_checked = isset($form_data['accept-terms']);


// --- Fonctions d'aide pour l'affichage des erreurs (spécifiques à cette vue) ---

/**
 * Retourne la classe CSS 'is-invalid' de Bootstrap si une erreur existe pour le champ spécifié.
 * @param string $field Le nom du champ (clé dans $form_errors).
 * @param array $errors Le tableau des erreurs ($form_errors).
 * @return string 'is-invalid' ou une chaîne vide.
 */
function getErrorClass($field, $errors) {
    return isset($errors[$field]) ? 'is-invalid' : '';
}

/**
 * Affiche le message d'erreur Bootstrap formaté pour un champ s'il existe une erreur.
 * @param string $field Le nom du champ (clé dans $form_errors).
 * @param array $errors Le tableau des erreurs ($form_errors).
 * @return string Le HTML du message d'erreur ou une chaîne vide.
 */
function displayError($field, $errors) {
    if (isset($errors[$field])) {
        // Échapper le message d'erreur pour la sécurité (XSS)
        return '<div class="invalid-feedback">' . htmlspecialchars($errors[$field], ENT_QUOTES, 'UTF-8') . '</div>';
    }
    return '';
}
?>

<div class="block-31" style="position: relative;">
  <div class="owl-carousel loop-block-31">
    <div class="block-30 block-30-sm item" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-7">
            <h2 class="heading">Mieux vaut donner que recevoir</h2>
          </div>
        </div>
      </div>
    </div>
    </div>
</div>

<div class="site-section fund-raisers">
  <div class="container">
    <div class="row mb-3 justify-content-center">
      <div class="col-md-8 text-center">
        <h2>Derniers dons</h2>
        <p class="lead">Rejoignez notre communauté de donateurs et aidez à faire une différence dans la vie des personnes dans le besoin.</p>
        </div>
    </div>

    <div class="row">
      <?php
      // Vérifier si la variable $latestDonations existe et n'est pas vide
      if (isset($latestDonations) && !empty($latestDonations)) :
          // Définir un mapping pour les causes pour un affichage plus convivial
          $cause_text_map_form_view = [
              'water' => "L'eau c'est la vie",
              'education' => "L'éducation des enfants",
              'shelter' => "Un abri pour tous"
              // Ajoutez d'autres causes si nécessaire
          ];

          foreach ($latestDonations as $donation_item) :
              // Traduire la cause du don pour l'affichage
              $display_cause_text = isset($cause_text_map_form_view[$donation_item['cause']])
                                ? htmlspecialchars($cause_text_map_form_view[$donation_item['cause']], ENT_QUOTES, 'UTF-8')
                                : htmlspecialchars($donation_item['cause'], ENT_QUOTES, 'UTF-8'); // Fallback

              // Calcul simple du temps écoulé (peut être affiné)
              $time_display = "Il y a peu";
              if (!empty($donation_item['donation_date'])) {
                  try {
                      $donation_datetime = new DateTime($donation_item['donation_date']);
                      $now = new DateTime();
                      $interval = $now->diff($donation_datetime);

                      if ($interval->y > 0) $time_display = "Il y a " . $interval->y . " an(s)";
                      elseif ($interval->m > 0) $time_display = "Il y a " . $interval->m . " mois";
                      elseif ($interval->d > 0) $time_display = "Il y a " . $interval->d . " jour(s)";
                      elseif ($interval->h > 0) $time_display = "Il y a " . $interval->h . " heure(s)";
                      elseif ($interval->i > 0) $time_display = "Il y a " . $interval->i . " minute(s)";
                      else $time_display = "À l'instant";
                  } catch (Exception $e) {
                      // En cas d'erreur de date, afficher un message par défaut
                      $time_display = "Date inconnue";
                       error_log("Erreur de format de date dans form.php pour latestDonations: " . $e->getMessage());
                  }
              }
      ?>
              <div class="col-md-6 col-lg-3 mb-5">
                  <div class="person-donate text-center">
                      <img src="images/person_<?php echo (($donation_item['id'] % 4) + 1); /* Varie l'image pour la démo */ ?>.jpg" alt="Donateur" class="img-fluid mb-3" style="max-width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                      <div class="donate-info">
                          <h4><?php echo htmlspecialchars($donation_item['donor_name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                          <span class="time d-block mb-3"><?php echo $time_display; ?></span>
                          <p>
                              A fait un don de <span class="text-success font-weight-bold"><?php echo htmlspecialchars(number_format((float)$donation_item['amount'], 2, ',', ' '), ENT_QUOTES, 'UTF-8'); ?> $</span><br>
                              <em>pour</em> <a href="#" class="link-underline fundraise-item" onclick="return false;" title="<?php echo $display_cause_text; ?>"><?php echo $display_cause_text; ?></a>
                          </p>
                      </div>
                  </div>
              </div>
          <?php
          endforeach; // Fin de la boucle sur $latestDonations
      else : // Si $latestDonations est vide ou non défini
      ?>
          <div class="col-12 text-center">
              <p>Aucun don récent à afficher pour le moment. Soyez le premier à contribuer !</p>
          </div>
      <?php endif; // Fin de la condition sur $latestDonations ?>
    </div>
  </div>
</div> <div class="site-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 text-center">
        <h2>Faire un don maintenant</h2>
        <p class="lead">Remplissez ce formulaire pour soutenir une cause qui vous tient à cœur. Chaque contribution compte.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-7 mx-auto"> <?php if (isset($form_errors['general'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($form_errors['general'], ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=donation&action=create" method="post" class="donation-form p-4 border rounded shadow-sm" id="donationForm" novalidate> 
            
            <div class="form-group">
                <label for="donor-name">Votre nom complet <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control py-2 <?php echo getErrorClass('donor-name', $form_errors); ?>" 
                       id="donor-name" 
                       name="donor-name" 
                       placeholder="Ex: Jean Dupont" 
                       value="<?php echo $donor_name_val; ?>"
                       aria-describedby="donor-name-help">
                <?php echo displayError('donor-name', $form_errors); ?>
                <span id="donor-name-error" class="text-danger small mt-1"></span>
            </div>
            
            <div class="form-group">
                <label for="donor-email">Votre adresse email <span class="text-danger">*</span></label>
                <input type="email" 
                       class="form-control py-2 <?php echo getErrorClass('donor-email', $form_errors); ?>" 
                       id="donor-email" 
                       name="donor-email" 
                       placeholder="Ex: jean.dupont@example.com" 
                       value="<?php echo $donor_email_val; ?>">
                <?php echo displayError('donor-email', $form_errors); ?>
                <span id="donor-email-error" class="text-danger small mt-1"></span>
            </div>
            
            <div class="form-group">
                <label for="donation-amount">Montant du don (en $) <span class="text-danger">*</span></label>
                <input type="number" 
                       step="0.01" 
                       min="1" 
                       max="10000" 
                       class="form-control py-2 <?php echo getErrorClass('donation-amount', $form_errors); ?>" 
                       id="donation-amount" 
                       name="donation-amount" 
                       placeholder="Ex: 50.00" 
                       value="<?php echo $donation_amount_val; ?>">
                <?php echo displayError('donation-amount', $form_errors); ?>
                <span id="donation-amount-error" class="text-danger small mt-1"></span>
            </div>
            
            <div class="form-group">
                <label for="donation-cause">Cause soutenue <span class="text-danger">*</span></label>
                <select class="form-control py-2 <?php echo getErrorClass('donation-cause', $form_errors); ?>" 
                        id="donation-cause" 
                        name="donation-cause">
                    <option value="">-- Sélectionnez une cause --</option>
                    <option value="water" <?php echo ($donation_cause_val == 'water') ? 'selected' : ''; ?>>L'eau c'est la vie. Eau potable en zone urbaine</option>
                    <option value="education" <?php echo ($donation_cause_val == 'education') ? 'selected' : ''; ?>>Les enfants ont besoin d'éducation</option>
                    <option value="shelter" <?php echo ($donation_cause_val == 'shelter') ? 'selected' : ''; ?>>Besoin d'abris pour les enfants en Afrique</option>
                </select>
                <?php echo displayError('donation-cause', $form_errors); ?>
                <span id="donation-cause-error" class="text-danger small mt-1"></span>
            </div>

            <fieldset class="mt-4 p-3 border rounded">
                <legend class="h6">Informations de paiement (Simulation)</legend>
                <div class="alert alert-info small">
                    <strong>Note :</strong> Les informations de carte bancaire ci-dessous sont pour la validation JavaScript uniquement.
                    <strong>Aucune donnée de paiement réelle n'est traitée ou stockée.</strong>
                </div>
                <div class="form-group">
                    <label for="card-number">Numéro de carte (pour validation JS)</label>
                    <input type="text" class="form-control py-2" id="card-number" placeholder="XXXX XXXX XXXX XXXX" autocomplete="cc-number"> 
                    <span id="card-number-error" class="text-danger small mt-1"></span>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="card-expiry">Date d'expiration (MM/AA) (pour validation JS)</label>
                        <input type="text" class="form-control py-2" id="card-expiry" placeholder="MM/AA" autocomplete="cc-exp">
                        <span id="card-expiry-error" class="text-danger small mt-1"></span>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="card-cvc">CVC (pour validation JS)</label>
                        <input type="text" class="form-control py-2" id="card-cvc" placeholder="XXX" autocomplete="cc-csc">
                        <span id="card-cvc-error" class="text-danger small mt-1"></span>
                    </div>
                </div>
            </fieldset>
            <div class="form-group mt-4">
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" 
                           class="custom-control-input <?php echo getErrorClass('accept-terms', $form_errors); ?>" 
                           id="accept-terms" 
                           name="accept-terms" 
                           value="accepted" 
                           <?php echo $accept_terms_checked ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="accept-terms">J'accepte les termes et conditions <span class="text-danger">*</span></label>
                    <?php if (isset($form_errors['accept-terms'])): ?>
                        <div class="invalid-feedback d-block"><?php echo htmlspecialchars($form_errors['accept-terms'], ENT_QUOTES, 'UTF-8'); ?></div>
                     <?php endif; ?>
                    <span id="accept-terms-error" class="text-danger d-block small mt-1"></span>
                </div>
            </div>
            
            <div class="form-group text-center"> <button type="submit" class="btn btn-primary btn-lg px-5 py-3">Faire un don maintenant</button>
            </div>
            <p class="text-center small text-muted mt-3"><span class="text-danger">*</span> Champs obligatoires</p>
        </form>
      </div>
    </div>
  </div>
</div> ```