<?php
// Emplacement: givehope-master/app/controllers/DonationController.php

/**
 * Contrôleur DonationController
 * Gère toutes les actions liées aux dons.
 */
class DonationController extends Controller
{
    private $donationModel;

    public function __construct()
    {
        $this->donationModel = $this->model('Donation');
    }

    public function index()
    {
        $numberOfLatestDonations = 4;
        $latestDonations = $this->donationModel->getAll($numberOfLatestDonations);

        $viewData = [
            'pageTitle' => 'Faire un don – GiveHope',
            'form_errors' => $_SESSION['form_errors'] ?? [],
            'form_data' => $_SESSION['form_data'] ?? [],
            'latestDonations' => $latestDonations
        ];

        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);

        $this->view('layouts/main', [
            'pageTitle' => $viewData['pageTitle'],
            'contentView' => 'donation/form',
            'viewData' => $viewData
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Accès non autorisé.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        $formData = [
            'donor_name' => trim(filter_input(INPUT_POST, 'donor-name', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''),
            'donor_email' => trim(filter_input(INPUT_POST, 'donor-email', FILTER_SANITIZE_EMAIL) ?? ''),
            'amount' => filter_input(INPUT_POST, 'donation-amount', FILTER_VALIDATE_FLOAT),
            'cause' => trim(filter_input(INPUT_POST, 'donation-cause', FILTER_SANITIZE_SPECIAL_CHARS) ?? '')
        ];
        $accept_terms = filter_has_var(INPUT_POST, 'accept-terms');
        $errors = [];

        if (empty($formData['donor_name'])) {
            $errors['donor-name'] = "Le nom est obligatoire.";
        } elseif (mb_strlen($formData['donor_name'], 'UTF-8') < 2) {
            $errors['donor-name'] = "Le nom doit contenir au moins 2 caractères.";
        } elseif (!preg_match("/^[A-Za-zÀ-ÿ\s'-.]+$/u", $formData['donor_name'])) {
            $errors['donor-name'] = "Le nom contient des caractères non autorisés.";
        }

        if (empty($formData['donor_email'])) {
            $errors['donor-email'] = "L'email est obligatoire.";
        } elseif (!filter_var($formData['donor_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['donor-email'] = "L'email n'est pas valide.";
        }

        if ($formData['amount'] === null || $formData['amount'] === false) {
            $errors['donation-amount'] = "Le montant est obligatoire et doit être un nombre.";
        } elseif ($formData['amount'] < 1.00) {
            $errors['donation-amount'] = "Montant minimum : 1.00 $.";
        } elseif ($formData['amount'] > 10000.00) {
            $errors['donation-amount'] = "Montant maximum : 10,000.00 $.";
        }

        $validCauses = ['water', 'education', 'shelter'];
        if (empty($formData['cause'])) {
            $errors['donation-cause'] = "Veuillez sélectionner une cause.";
        } elseif (!in_array($formData['cause'], $validCauses, true)) {
            $errors['donation-cause'] = "La cause sélectionnée n'est pas valide.";
        }

        if (!$accept_terms) {
            $errors['accept-terms'] = "Vous devez accepter les termes et conditions.";
        }

        if (empty($errors)) {
            $donationId = $this->donationModel->create($formData);
            if ($donationId !== false && $donationId > 0) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Merci ! Don (ID: ' . $donationId . ') enregistré.'];
                $this->redirect('index.php?controller=donation&action=display&id=' . $donationId);
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Erreur technique lors de l\'enregistrement.'];
                $_SESSION['form_data'] = $formData;
                if ($accept_terms) $_SESSION['form_data']['accept-terms'] = 'accepted';
                $this->redirect('index.php?controller=donation&action=index');
            }
        } else {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $this->redirect('index.php?controller=donation&action=index');
        }
    }

    public function display()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false || $id === null) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de don invalide.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        $donation = $this->donationModel->findById($id);
        if ($donation === false || empty($donation)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Don (ID: ' . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . ') introuvable.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        $donation_history = [];
        if (!empty($donation['donor_email'])) {
            $donation_history = $this->donationModel->getHistoryByEmail($donation['donor_email'], $id, 5);
        }

        $viewData = [
            'pageTitle' => 'Confirmation de don – GiveHope',
            'donation' => $donation,
            'donation_history' => $donation_history,
            'message' => $_SESSION['message'] ?? null
        ];
        unset($_SESSION['message']);

        $this->view('layouts/main', [
            'pageTitle' => $viewData['pageTitle'],
            'contentView' => 'donation/display',
            'viewData' => $viewData
        ]);
    }

    public function edit()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false || $id === null) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de don invalide pour modification.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        $donation_to_edit = $this->donationModel->findById($id);
        if ($donation_to_edit === false || empty($donation_to_edit)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Don (ID: ' . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . ') introuvable pour modification.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        $form_data_for_view = $_SESSION['form_data'] ?? $donation_to_edit;
        $form_errors = $_SESSION['form_errors'] ?? [];
        unset($_SESSION['form_data'], $_SESSION['form_errors']);

        $viewData = [
            'pageTitle' => 'Modifier don – GiveHope',
            'donation' => [
                'donor_name' => $form_data_for_view['donor_name'] ?? ($form_data_for_view['donor-name'] ?? ''),
                'donor_email' => $form_data_for_view['donor_email'] ?? ($form_data_for_view['donor-email'] ?? ''),
                'amount' => $form_data_for_view['donation-amount'] ?? ($form_data_for_view['amount'] ?? ''),
                'cause' => $form_data_for_view['donation-cause'] ?? ($form_data_for_view['cause'] ?? ''),
            ],
            'form_errors' => $form_errors,
            'donation_id_to_modify' => $id
        ];

        $this->view('layouts/main', [
            'pageTitle' => $viewData['pageTitle'],
            'contentView' => 'donation/modifier_form',
            'viewData' => $viewData
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Accès non autorisé.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        $id = filter_input(INPUT_POST, 'donation_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false || $id === null) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de don manquant pour la mise à jour.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        $formData = [
            'donor_name' => trim(filter_input(INPUT_POST, 'donor-name', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''),
            'donor_email' => trim(filter_input(INPUT_POST, 'donor-email', FILTER_SANITIZE_EMAIL) ?? ''),
            'amount' => filter_input(INPUT_POST, 'donation-amount', FILTER_VALIDATE_FLOAT),
            'cause' => trim(filter_input(INPUT_POST, 'donation-cause', FILTER_SANITIZE_SPECIAL_CHARS) ?? '')
        ];
        $errors = [];

        // Validation (similaire à create)
        if (empty($formData['donor_name'])) { $errors['donor-name'] = "Le nom est obligatoire."; }
        elseif (mb_strlen($formData['donor_name'], 'UTF-8') < 2) { $errors['donor-name'] = "Le nom doit faire au moins 2 caractères.";}
        elseif (!preg_match("/^[A-Za-zÀ-ÿ\s'-.]+$/u", $formData['donor_name'])) { $errors['donor-name'] = "Le nom contient des caractères invalides.";}

        if (empty($formData['donor_email'])) { $errors['donor-email'] = "L'email est obligatoire."; }
        elseif (!filter_var($formData['donor_email'], FILTER_VALIDATE_EMAIL)) { $errors['donor-email'] = "L'email n'est pas valide."; }

        if ($formData['amount'] === null || $formData['amount'] === false) { $errors['donation-amount'] = "Le montant est obligatoire et doit être numérique."; }
        elseif ($formData['amount'] < 1.00) { $errors['donation-amount'] = "Montant minimum : 1.00 $."; }
        elseif ($formData['amount'] > 10000.00) { $errors['donation-amount'] = "Montant maximum : 10,000.00 $."; }

        $validCauses = ['water', 'education', 'shelter'];
        if (empty($formData['cause'])) { $errors['donation-cause'] = "La sélection d'une cause est obligatoire."; }
        elseif (!in_array($formData['cause'], $validCauses, true)) { $errors['donation-cause'] = "La cause sélectionnée n'est pas valide."; }

        if (empty($errors)) {
            if ($this->donationModel->update($id, $formData)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Don (ID: ' . $id . ') modifié avec succès !'];
                $this->redirect('index.php?controller=donation&action=display&id=' . $id);
            } else {
                $_SESSION['message'] = ['type' => 'warning', 'text' => 'Aucune modification apportée au don (ID: ' . $id . ') ou erreur.'];
                $_SESSION['form_data'] = $_POST;
                $this->redirect('index.php?controller=donation&action=edit&id=' . $id);
            }
        } else {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $this->redirect('index.php?controller=donation&action=edit&id=' . $id);
        }
    }

    public function delete()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false || $id === null) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'ID de don invalide pour suppression.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        $donation_exists = $this->donationModel->findById($id);
        if (!$donation_exists) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Don (ID: ' . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . ') introuvable pour suppression.'];
            $this->redirect('index.php?controller=donation&action=index');
        }

        if ($this->donationModel->delete($id)) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Don (ID: ' . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . ') supprimé.'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Erreur lors de la suppression du don (ID: ' . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . ').'];
        }
        $this->redirect('index.php?controller=donation&action=index');
    }
}
?>