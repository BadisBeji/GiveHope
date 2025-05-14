<?php
// Emplacement: givehope-master/app/views/admin/dashboard.php
/**
 * Vue pour le tableau de bord d'administration des dons.
 * Affiche une liste de tous les dons avec des options pour les modifier ou les supprimer.
 *
 * Variables attendues depuis AdminController dans $viewData :
 * - $pageTitle (string)
 * - $donations (array) : Tableau de tous les dons.
 * - $message (array|null) : Message flash.
 */

// Helper pour formater les dates (peut être mis dans un fichier d'aide global plus tard)
if (!function_exists('format_dashboard_date')) {
    function format_dashboard_date($date_string) {
        if (empty($date_string)) return 'N/A';
        try {
            $dateObj = new DateTimeImmutable($date_string);
            if (class_exists('IntlDateFormatter')) {
                $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT);
                return $formatter->format($dateObj);
            }
            return $dateObj->format('d/m/Y H:i');
        } catch (Exception $e) {
            return htmlspecialchars($date_string, ENT_QUOTES, 'UTF-8') . ' (format brut)';
        }
    }
}
?>

<div class="block-31" style="position: relative;">
  <div class="owl-carousel loop-block-31">
    <div class="block-30 block-30-sm item" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-7">
            <h2 class="heading"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') : 'Tableau de Bord'; ?></h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="site-section">
    <div class="container-fluid"> <div class="row mb-3">
            <div class="col-md-12 text-center">
                <h3>Liste des Dons Enregistrés</h3>
                <p class="lead text-muted">Gérez les dons reçus via la plateforme.</p>
            </div>
        </div>

        <?php
        // Affichage du message flash (si redirigé ici après une action)
        // Le layout principal gère déjà l'affichage des messages si $viewData['message'] est défini.
        // Si vous voulez un message spécifique uniquement sur cette page, vous pouvez le faire ici.
        // Exemple: if(isset($message) && $message) { /* afficher $message */ }
        ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom du Donateur</th>
                        <th>Email</th>
                        <th class="text-right">Montant ($)</th>
                        <th>Cause</th>
                        <th>Date du Don</th>
                        <th>Référence</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($donations) && !empty($donations)) : ?>
                        <?php foreach ($donations as $don) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string)$don['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($don['donor_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($don['donor_email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-right"><?php echo htmlspecialchars(number_format((float)$don['amount'], 2, ',', ' '), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($don['cause'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo format_dashboard_date($don['donation_date']); ?></td>
                                <td><?php echo isset($don['reference_number']) ? htmlspecialchars($don['reference_number'], ENT_QUOTES, 'UTF-8') : 'N/A'; ?></td>
                                <td class="text-center">
                                    <a href="index.php?controller=donation&action=display&id=<?php echo (int)$don['id']; ?>" class="btn btn-sm btn-info mb-1" title="Voir Détails">
                                        <i class="icon-eye"></i>
                                    </a>
                                    <a href="index.php?controller=admin&action=editDonationForm&id=<?php echo (int)$don['id']; ?>" class="btn btn-sm btn-warning mb-1 text-white" title="Modifier">
                                        <i class="icon-pencil"></i>
                                    </a>
                                    <a href="index.php?controller=admin&action=deleteDonation&id=<?php echo (int)$don['id']; ?>" 
                                       class="btn btn-sm btn-danger mb-1" 
                                       title="Supprimer"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don (ID: <?php echo (int)$don['id']; ?>) ? Cette action est irréversible.');">
                                        <i class="icon-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucun don enregistré pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="index.php?controller=donation&action=index" class="btn btn-secondary">
                    <i class="icon-plus mr-2"></i>Faire un nouveau don (page publique)
                </a>
            </div>
        </div>

    </div>
</div>
```