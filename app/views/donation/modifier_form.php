<?php
// Emplacement: givehope-master/app/views/donation/modifier_form.php

/**
 * Vue pour le formulaire de modification d'un don existant.
 * Variables attendues dans $viewData :
 * - $donation (array) : Données actuelles du don pour pré-remplir.
 * - $form_errors (array) : Erreurs de validation d'une soumission précédente.
 * - $donation_id_to_modify (int) : ID du don en cours de modification.
 */

if (!isset($donation_id_to_modify) || !filter_var($donation_id_to_modify, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
    echo "<div class='container mt-5'><div class='alert alert-danger text-center' role='alert'><strong>Erreur :</strong> ID du don à modifier manquant ou invalide.</div></div>";
    error_log("Erreur vue modifier_form.php: \$donation_id_to_modify manquant ou invalide.");
    return;
}
if (!isset($donation) || !is_array($donation)) {
    echo "<div class='container mt-5'><div class='alert alert-danger text-center' role='alert'><strong>Erreur :</strong> Données du don à modifier non disponibles.</div></div>";
    error_log("Erreur vue modifier_form.php: \$donation (données du don) manquant.");
    return;
}

// Pré-remplissage des champs.
$donor_name_val = isset($donation['donor_name']) ? htmlspecialchars($donation['donor_name'], ENT_QUOTES, 'UTF-8') : '';
$donor_email_val = isset($donation['donor_email']) ? htmlspecialchars($donation['donor_email'], ENT_QUOTES, 'UTF-8') : '';
$donation_amount_val = isset($donation['amount']) ? htmlspecialchars((string)$donation['amount'], ENT_QUOTES, 'UTF-8') : '';
$donation_cause_val = isset($donation['cause']) ? $donation['cause'] : '';

// Fonctions d'aide (déjà définies si form.php a été inclus par le même layout, mais on les redéfinit ici par sécurité si la vue est appelée seule).
if (!function_exists('getErrorClass')) {
    function getErrorClass($field, $errors) {
        return isset($errors[$field]) ? 'is-invalid' : '';
    }
}
if (!function_exists('displayError')) {
    function displayError($field, $errors) {
        if (isset($errors[$field])) {
            return '<div class="invalid-feedback">' . htmlspecialchars($errors[$field], ENT_QUOTES, 'UTF-8') . '</div>';
        }
        return '';
    }
}
?>

<div class="block-31" style="position: relative;">
    <div class="owl-carousel loop-block-31 ">
        <div class="block-30 block-30-sm item" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
          <div class="container">
            <div class="row align-items-center justify-content-center text-center">
              <div class="col-md-7">
                <h2 class="heading">Modifier votre don</h2>
                <p class="lead text-white">Mettez à jour les informations de votre contribution.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

<div class="site-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10 text-center mb-4">
        <h2>Modification du don (ID: <?php echo htmlspecialchars($donation_id_to_modify, ENT_QUOTES, 'UTF-8'); ?>)</h2>
        <p class="lead text-muted">Veuillez mettre à jour les informations ci-dessous.</p>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">

        <?php if (isset($form_errors['general'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($form_errors['general'], ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=donation&action=update" method="post" class="donation-form p-4 border rounded shadow-sm" id="modificationForm" novalidate>
            <input type="hidden" name="donation_id" value="<?php echo htmlspecialchars($donation_id_to_modify, ENT_QUOTES, 'UTF-8'); ?>">
            
            <div class="form-group">
                <label for="donor-name">Votre nom complet <span class="text-danger">*</span></label>
                <input type="text" class="form-control py-2 <?php echo getErrorClass('donor-name', $form_errors); ?>" id="donor-name" name="donor-name" placeholder="Entrez votre nom" value="<?php echo $donor_name_val; ?>">
                <?php echo displayError('donor-name', $form_errors); ?>
                <span id="donor-name-error" class="text-danger small mt-1"></span>
            </div>
            
            <div class="form-group">
                <label for="donor-email">Votre adresse email <span class="text-danger">*</span></label>
                <input type="email" class="form-control py-2 <?php echo getErrorClass('donor-email', $form_errors); ?>" id="donor-email" name="donor-email" placeholder="exemple@domaine.com" value="<?php echo $donor_email_val; ?>">
                <?php echo displayError('donor-email', $form_errors); ?>
                <span id="donor-email-error" class="text-danger small mt-1"></span>
            </div>
            
            <div class="form-group">
                <label for="donation-amount">Montant du don (en $) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="1" max="10000" class="form-control py-2 <?php echo getErrorClass('donation-amount', $form_errors); ?>" id="donation-amount" name="donation-amount" placeholder="Exemple : 50.00" value="<?php echo $donation_amount_val; ?>">
                <?php echo displayError('donation-amount', $form_errors); ?>
                <span id="donation-amount-error" class="text-danger small mt-1"></span>
            </div>
            
            <div class="form-group">
                <label for="donation-cause">Cause soutenue <span class="text-danger">*</span></label>
                <select class="form-control py-2 <?php echo getErrorClass('donation-cause', $form_errors); ?>" id="donation-cause" name="donation-cause">
                    <option value="">-- Sélectionnez une cause --</option>
                    <option value="water" <?php echo ($donation_cause_val === 'water') ? 'selected' : ''; ?>>L'eau c'est la vie. Eau potable en zone urbaine</option>
                    <option value="education" <?php echo ($donation_cause_val === 'education') ? 'selected' : ''; ?>>Les enfants ont besoin d'éducation</option>
                    <option value="shelter" <?php echo ($donation_cause_val === 'shelter') ? 'selected' : ''; ?>>Besoin d'abris pour les enfants en Afrique</option>
                </select>
                 <?php echo displayError('donation-cause', $form_errors); ?>
                 <span id="donation-cause-error" class="text-danger small mt-1"></span>
            </div>

            <div class="alert alert-warning mt-4 small">
                <strong>Note :</strong> Les informations de paiement ne sont pas modifiables ici.
            </div>
            
            <div class="form-group mt-4 d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary btn-lg px-5 py-2">
                    <i class="icon-save mr-2"></i>Enregistrer
                </button>
                <a href="index.php?controller=donation&action=display&id=<?php echo htmlspecialchars($donation_id_to_modify, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-secondary px-4 py-2">
                    <i class="icon-close mr-2"></i>Annuler
                </a>
            </div>
            <p class="text-center small text-muted mt-3"><span class="text-danger">*</span> Champs obligatoires</p>
        </form>
      </div>
    </div>
  </div>
</div>
```