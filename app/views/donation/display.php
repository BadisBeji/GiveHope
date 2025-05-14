<?php
// Emplacement: givehope-master/app/views/donation/display.php

/**
 * Vue pour afficher les détails d'un don (confirmation ou consultation).
 * Cette vue est chargée par le DonationController, action display().
 *
 * Variables attendues (passées via le tableau $viewData extrait par layouts/main.php) :
 * - $donation (array) : Tableau associatif contenant les détails du don principal à afficher.
 * Doit contenir au moins : id, donor_name, donor_email, amount, cause, donation_date, reference_number.
 * - $donation_history (array) : Tableau de tableaux associatifs, représentant les dons précédents
 * du même donateur (basé sur l'email). Chaque sous-tableau a une structure similaire à $donation.
 * Peut être vide si aucun historique n'est trouvé.
 * - $message (array|null) : Message flash (généralement un message de succès après la création/modification du don).
 * Format attendu : ['type' => 'success|error|warning', 'text' => 'Message à afficher']
 */

// --- Vérification initiale des données essentielles ---
if (!isset($donation) || empty($donation) || !isset($donation['id'])) {
    echo "<div class='container mt-5'><div class='alert alert-danger text-center' role='alert'><strong>Erreur :</strong> Les détails du don sont actuellement indisponibles ou le don demandé est introuvable.</div></div>";
    error_log("Erreur dans la vue display.php: La variable \$donation est manquante ou invalide.");
    return; // Arrêter l'exécution de cette vue si les données de base sont absentes.
}

// --- Préparation des données pour l'affichage (formatage, etc.) ---

// Mapping des identifiants de cause vers des textes plus descriptifs.
$cause_text_map_display_view = [
    'water' => "L'eau c'est la vie. Accès à l'eau potable en zone urbaine.",
    'education' => "L'éducation pour tous les enfants, un avenir meilleur.",
    'shelter' => "Fournir un abri sûr et décent aux enfants en Afrique."
];
$display_cause_text = isset($cause_text_map_display_view[$donation['cause']])
                ? htmlspecialchars($cause_text_map_display_view[$donation['cause']], ENT_QUOTES, 'UTF-8')
                : htmlspecialchars($donation['cause'], ENT_QUOTES, 'UTF-8');

// Formatage de la date du don.
$donation_date_formatted = 'Date inconnue';
if (!empty($donation['donation_date'])) {
    try {
        $dateObj = new DateTimeImmutable($donation['donation_date']);
        // Utilisation de IntlDateFormatter pour un formatage localisé (nécessite l'extension intl).
        if (class_exists('IntlDateFormatter')) {
            $dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT, null, IntlDateFormatter::GREGORIAN);
            $donation_date_formatted = $dateFormatter->format($dateObj);
        } else {
            $donation_date_formatted = $dateObj->format('d/m/Y H:i:s'); // Fallback simple.
        }
    } catch (Exception $e) {
        error_log("Erreur de formatage de date dans display.php (donation_date): " . $e->getMessage());
        $donation_date_formatted = htmlspecialchars($donation['donation_date'], ENT_QUOTES, 'UTF-8') . " (format brut)";
    }
}

// Calcul d'impact (exemple simple, à adapter).
$impact_people = 0;
if (isset($donation['amount']) && is_numeric($donation['amount']) && (float)$donation['amount'] > 0) {
    $impact_people = floor((float)$donation['amount'] / 10); // Exemple: 10$ = aide pour 1 personne.
}
?>

<div class="block-31" style="position: relative;">
  <div class="owl-carousel loop-block-31">
    <div class="block-30 block-30-sm item" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-7">
            <h2 class="heading">Merci pour votre générosité !</h2>
            <p class="lead text-white">Votre soutien fait la différence.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="site-section fund-raisers py-5">
  <div class="container">
    <div class="row mb-5 justify-content-center">
      <div class="col-md-10 text-center">
        <h2 class="font-weight-bold">Confirmation de votre don</h2>
        <p class="lead text-muted">Nous vous remercions sincèrement pour votre contribution.</p>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-9 col-lg-8">
        <div class="card shadow-lg mb-5">
          <div class="card-header bg-success text-white">
            <h3 class="mb-0">Récapitulatif de votre donation</h3>
          </div>
          <div class="card-body p-4">
            <div class="donation-details">
              <dl class="row">
                <dt class="col-sm-4 font-weight-bold text-muted">Donateur :</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($donation['donor_name'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4 font-weight-bold text-muted">Email :</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($donation['donor_email'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4 font-weight-bold text-muted">Montant du don :</dt>
                <dd class="col-sm-8"><strong class="text-success h5"><?php echo htmlspecialchars(number_format((float)$donation['amount'], 2, ',', ' '), ENT_QUOTES, 'UTF-8'); ?> $</strong></dd>

                <dt class="col-sm-4 font-weight-bold text-muted">Cause soutenue :</dt>
                <dd class="col-sm-8"><?php echo $display_cause_text; ?></dd>

                <dt class="col-sm-4 font-weight-bold text-muted">Date du don :</dt>
                <dd class="col-sm-8"><?php echo $donation_date_formatted; ?></dd>

                <dt class="col-sm-4 font-weight-bold text-muted">Numéro de référence :</dt>
                <dd class="col-sm-8"><code><?php echo isset($donation['reference_number']) ? htmlspecialchars($donation['reference_number'], ENT_QUOTES, 'UTF-8') : 'N/A'; ?></code></dd>
              </dl>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center mt-4">
              <p class="text-muted">Un reçu fiscal (simulé) pourrait être envoyé à votre adresse email.</p>
              
              <div class="impact-message p-3 my-4 bg-light rounded border">
                <h4 class="h5 text-primary">Votre impact estimé</h4>
                <p class="mb-0">Grâce à votre don de <strong class="text-success"><?php echo htmlspecialchars(number_format((float)$donation['amount'], 2, ',', ' '), ENT_QUOTES, 'UTF-8'); ?> $</strong>, vous aidez environ <strong class="text-info"><?php echo $impact_people; ?></strong> personne(s) (estimation).</p>
              </div>
              
              <div class="mt-4 donation-actions">
                <a href="index.php?controller=donation&action=index" class="btn btn-primary btn-lg mr-2 mb-2 px-4">
                    <i class="icon-home mr-2"></i>Accueil
                </a>
                <a href="#" class="btn btn-outline-primary mr-2 mb-2 px-4" onclick="alert('Fonctionnalité de téléchargement du reçu non implémentée.'); return false;">
                    <i class="icon-download mr-2"></i>Télécharger reçu
                </a>
                <a href="index.php?controller=donation&action=edit&id=<?php echo (int)$donation['id']; ?>" class="btn btn-warning mr-2 mb-2 px-4 text-white">
                     <i class="icon-pencil mr-2"></i>Modifier
                </a>
                <a href="index.php?controller=donation&action=delete&id=<?php echo (int)$donation['id']; ?>" class="btn btn-danger mb-2 px-4" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don (ID: <?php echo (int)$donation['id']; ?>) ? Cette action est irréversible.');">
                    <i class="icon-trash mr-2"></i>Supprimer
                </a>
              </div>
            </div>
          </div>
        </div>

        <?php if (isset($donation_history) && is_array($donation_history) && !empty($donation_history)): ?>
            <div class="card mt-5 shadow-sm mb-4">
              <div class="card-header bg-light">
                <h3 class="mb-0 h4">Historique de vos dons</h3>
                <p class="mb-0 text-muted small">Les <?php echo count($donation_history); ?> derniers dons avec l'email <?php echo htmlspecialchars($donation['donor_email'], ENT_QUOTES, 'UTF-8'); ?>.</p>
              </div>
              <div class="card-body p-0">
                  <ul class="list-group list-group-flush">
                      <?php
                      foreach ($donation_history as $history_item):
                          $h_cause_text = isset($cause_text_map_display_view[$history_item['cause']])
                                        ? htmlspecialchars($cause_text_map_display_view[$history_item['cause']], ENT_QUOTES, 'UTF-8')
                                        : htmlspecialchars($history_item['cause'], ENT_QUOTES, 'UTF-8');
                          
                          $h_date_formatted = 'Date inconnue';
                          if (!empty($history_item['donation_date'])) {
                              try {
                                  $h_dateObj = new DateTimeImmutable($history_item['donation_date']);
                                  if (class_exists('IntlDateFormatter')) {
                                      $h_dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
                                      $h_date_formatted = $h_dateFormatter ? $h_dateFormatter->format($h_dateObj) : $h_dateObj->format('d M Y');
                                  } else {
                                      $h_date_formatted = $h_dateObj->format('d/m/Y');
                                  }
                              } catch (Exception $e) { /* Garder 'Date inconnue' ou logguer */ }
                          }
                      ?>
                          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                              <div>
                                  <span class="d-block">
                                      <small class="text-muted"><?php echo $h_date_formatted; ?> – </small>
                                      <strong class="text-success"><?php echo htmlspecialchars(number_format((float)$history_item['amount'], 2, ',', ' '), ENT_QUOTES, 'UTF-8'); ?> $</strong>
                                  </span>
                                  <small class="d-block">Pour : <em><?php echo $h_cause_text; ?></em></small>
                                  <small class="text-muted d-block">
                                      Réf: <?php echo isset($history_item['reference_number']) ? htmlspecialchars($history_item['reference_number'], ENT_QUOTES, 'UTF-8') : 'N/A'; ?>
                                  </small>
                              </div>
                              <div class="mt-2 mt-md-0">
                                  <a href="index.php?controller=donation&action=display&id=<?php echo (int)$history_item['id']; ?>" class="btn btn-sm btn-outline-info px-3">
                                      <i class="icon-eye mr-1"></i>Voir
                                  </a>
                              </div>
                          </li>
                      <?php endforeach; ?>
                  </ul>
              </div>
            </div>
        <?php elseif (isset($donation_history) && is_array($donation_history) && empty($donation_history) && !empty($donation['donor_email'])): ?>
            <div class="card mt-5 shadow-sm mb-4">
                <div class="card-header bg-light"><h3 class="mb-0 h4">Historique de vos dons</h3></div>
                <div class="card-body text-center">
                    <p class="text-muted mb-0">Aucun autre don n'a été trouvé pour cette adresse email.</p>
                </div>
            </div>
        <?php endif; ?>

        <div class="card mt-5 shadow-sm">
          <div class="card-header bg-light"> <h3 class="mb-0 h4">Partagez votre générosité</h3> </div>
          <div class="card-body text-center">
            <p class="text-muted">Inspirez d'autres personnes à contribuer (fonctionnalité simulée) :</p>
            <div class="social-sharing mt-3">
              <a href="#" class="btn btn-outline-primary mx-1 mb-2 px-3" onclick="alert('Partage Facebook non implémenté.'); return false;"><i class="icon-facebook mr-1"></i> Facebook</a>
              <a href="#" class="btn btn-outline-info mx-1 mb-2 px-3" onclick="alert('Partage Twitter non implémenté.'); return false;"><i class="icon-twitter mr-1"></i> Twitter</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```