<?php
// Emplacement: givehope-master/app/controllers/AdminController.php

class AdminController extends Controller
{
    private $donationModel;

    public function __construct()
    {
        // Pour ce dashboard, nous aurons besoin du modèle Donation
        // pour récupérer et gérer les dons.
        $this->donationModel = $this->model('Donation');

        // Optionnel : Mettre en place une vérification de session ici
        // pour s'assurer que seul un administrateur connecté peut accéder à ce contrôleur.
        // Exemple :
        // if (!isset($_SESSION['user_is_admin']) || $_SESSION['user_is_admin'] !== true) {
        //     $_SESSION['message'] = ['type' => 'error', 'text' => 'Accès non autorisé.'];
        //     $this->redirect('index.php?controller=user&action=login'); // Rediriger vers une page de connexion admin
        // }
    }

    /**
     * Affiche le tableau de bord principal avec la liste de tous les dons.
     */
    public function index() // Action par défaut pour le dashboard
    {
        // Récupérer tous les dons depuis le modèle
        $donations = $this->donationModel->getAll(); // La méthode getAll() de DonationModel récupère tous les dons

        $viewData = [
            'pageTitle' => 'Tableau de Bord - Gestion des Dons',
            'donations' => $donations,
            'message' => $_SESSION['message'] ?? null // Pour afficher des messages de succès/erreur après une action
        ];
        unset($_SESSION['message']); // Nettoyer le message après l'avoir récupéré

        $this->view('layouts/main', [
            'pageTitle' => $viewData['pageTitle'],
            'contentView' => 'admin/dashboard', // Nouvelle vue que nous allons créer
            'viewData' => $viewData
        ]);
    }

    /**
     * Gère la suppression d'un don.
     * Appelée via un lien depuis le dashboard.
     * Réutilise la logique du DonationController pour la suppression.
     */
    public function deleteDonation()
    {
        // Récupérer l'ID du don à supprimer depuis l'URL (paramètre GET 'id')
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($id === false || $id === null) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de don invalide pour la suppression.'];
        } else {
            // Vérifier si le don existe avant de tenter de le supprimer
            $donation_exists = $this->donationModel->findById($id);
            if (!$donation_exists) {
                 $_SESSION['message'] = ['type' => 'error', 'text' => 'Le don (ID: ' . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . ') à supprimer est introuvable.'];
            } else {
                if ($this->donationModel->delete($id)) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Don (ID: ' . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . ') supprimé avec succès.'];
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Erreur lors de la suppression du don (ID: ' . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . ').'];
                }
            }
        }
        // Rediriger vers le tableau de bord principal après la tentative de suppression
        $this->redirect('index.php?controller=admin&action=index');
    }

    /**
     * Affiche le formulaire de modification d'un don (redirige vers l'action edit du DonationController).
     * Pourrait aussi avoir sa propre vue de modification admin si nécessaire.
     */
    public function editDonationForm()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($id === false || $id === null) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de don invalide pour la modification.'];
            $this->redirect('index.php?controller=admin&action=index');
        } else {
            // Rediriger vers le formulaire d'édition existant dans DonationController
            // L'action 'update' du DonationController se chargera de la mise à jour
            // et redirigera ensuite vers la page 'display' du don.
            // Vous pourriez vouloir une redirection différente après la mise à jour depuis le dashboard.
            $this->redirect('index.php?controller=donation&action=edit&id=' . $id);
        }
    }

    // Note : L'action de mise à jour (update) sera gérée par le DonationController::update
    // après soumission du formulaire de modification. Si vous voulez un flux de mise à jour
    // qui reste entièrement dans le contexte "admin" (ex: redirection vers le dashboard admin
    // après update), vous devriez dupliquer/adapter la logique de `DonationController::update` ici
    // et créer une vue `admin/modifier_form.php` distincte si nécessaire.
    // Pour la simplicité, nous réutilisons le flux de modification existant.

}
?>