<?php
// Emplacement: givehope-master/app/models/Donation.php

/**
 * Classe Donation (Modèle)
 *
 * Responsabilités :
 * - Gérer la logique métier spécifique aux dons.
 * - Interagir avec la table 'donations' de la base de données.
 * - Effectuer les opérations CRUD (Create, Read, Update, Delete) pour les dons.
 * - Assurer la sécurité des requêtes à la base de données en utilisant PDO et des requêtes préparées.
 */
class Donation
{
    private $db; // Instance de la connexion PDO (objet PDO)

    /**
     * Constructeur de la classe Donation.
     * Initialise la connexion à la base de données en obtenant l'instance unique de la classe Database.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère tous les dons de la base de données, ou un nombre limité.
     *
     * @param int|null $limit Le nombre maximum de dons à retourner.
     * @return array Tableau des dons, ou un tableau vide en cas d'erreur.
     */
    public function getAll($limit = null)
    {
        try {
            $sql = "SELECT id, donor_name, donor_email, amount, cause, donation_date, reference_number 
                    FROM donations 
                    ORDER BY donation_date DESC";

            if ($limit !== null && filter_var($limit, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $sql .= " LIMIT :limit";
            }
            
            $stmt = $this->db->prepare($sql);

            if ($limit !== null && filter_var($limit, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Modèle Donation - Erreur PDO dans getAll(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un don spécifique par son ID.
     *
     * @param int $id L'ID du don.
     * @return array|false Données du don si trouvé, sinon false.
     */
    public function findById($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            error_log("Modèle Donation - findById: ID invalide fourni (valeur: '" . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . "').");
            return false;
        }

        try {
            $sql = "SELECT id, donor_name, donor_email, amount, cause, donation_date, reference_number 
                    FROM donations 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Modèle Donation - Erreur PDO dans findById (ID: " . (int)$id . "): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère l'historique des N derniers dons pour un email donné, excluant un ID spécifique.
     *
     * @param string $email Email du donateur.
     * @param int $current_id ID du don actuel à exclure.
     * @param int $limit Nombre maximum de résultats.
     * @return array Historique des dons, ou tableau vide en cas d'erreur.
     */
    public function getHistoryByEmail($email, $current_id, $limit = 5)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             error_log("Modèle Donation - getHistoryByEmail: Email invalide fourni (valeur: '" . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "').");
             return [];
        }
        if (!filter_var($current_id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
             error_log("Modèle Donation - getHistoryByEmail: Current ID invalide fourni (valeur: '" . htmlspecialchars((string)$current_id, ENT_QUOTES, 'UTF-8') . "').");
             return [];
        }
        $limitSanitized = filter_var($limit, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($limitSanitized === false) {
            $limitSanitized = 5;
        }

        try {
            $sql = "SELECT id, amount, cause, donation_date, reference_number
                    FROM donations
                    WHERE donor_email = :email AND id != :current_id
                    ORDER BY donation_date DESC
                    LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':current_id', $current_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limitSanitized, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Modèle Donation - Erreur PDO dans getHistoryByEmail (Email: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crée un nouveau don dans la base de données.
     *
     * @param array $data Données du don ['donor_name', 'donor_email', 'amount', 'cause'].
     * @return string|false L'ID du nouveau don si succès, sinon false.
     */
    public function create($data)
    {
        $reference_number = 'DON-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(4)));

        $donor_name = isset($data['donor_name']) ? trim(strip_tags((string)$data['donor_name'])) : '';
        $donor_email = isset($data['donor_email']) ? trim((string)$data['donor_email']) : '';
        $amount = isset($data['amount']) ? (float)$data['amount'] : 0.0;
        $cause = isset($data['cause']) ? trim(strip_tags((string)$data['cause'])) : '';

        if (empty($donor_name) || empty($donor_email) || $amount <= 0 || empty($cause)) {
            error_log("Modèle Donation - Erreur create: Données essentielles vides ou invalides. Données: " . print_r($data, true));
            return false;
        }

        try {
            $sql = "INSERT INTO donations (donor_name, donor_email, amount, cause, reference_number, donation_date)
                    VALUES (:donor_name, :donor_email, :amount, :cause, :reference_number, NOW())";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':donor_name', $donor_name, PDO::PARAM_STR);
            $stmt->bindParam(':donor_email', $donor_email, PDO::PARAM_STR);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':cause', $cause, PDO::PARAM_STR);
            $stmt->bindParam(':reference_number', $reference_number, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            error_log("Modèle Donation - Erreur create: stmt->execute() a retourné false sans lever d'exception PDO.");
            return false;
        } catch (PDOException $e) {
            error_log("Modèle Donation - Erreur PDO dans create(): " . $e->getMessage() . " | Données: " . print_r($data, true));
            if ($e->getCode() == 23000) { 
                 error_log("Modèle Donation - Tentative d'insertion violant une contrainte d'unicité.");
            }
            return false;
        }
    }

    /**
     * Met à jour un don existant.
     *
     * @param int $id ID du don à mettre à jour.
     * @param array $data Nouvelles données ['donor_name', 'donor_email', 'amount', 'cause'].
     * @return bool True si succès, false sinon.
     */
    public function update($id, $data)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            error_log("Modèle Donation - update: ID invalide fourni (valeur: '" . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . "').");
            return false;
        }

        $donor_name = isset($data['donor_name']) ? trim(strip_tags((string)$data['donor_name'])) : '';
        $donor_email = isset($data['donor_email']) ? trim((string)$data['donor_email']) : '';
        $amount = isset($data['amount']) ? (float)$data['amount'] : 0.0;
        $cause = isset($data['cause']) ? trim(strip_tags((string)$data['cause'])) : '';

        if (empty($donor_name) || empty($donor_email) || $amount <= 0 || empty($cause)) {
            error_log("Modèle Donation - Erreur update: Données essentielles vides/invalides pour ID $id. Données: " . print_r($data, true));
            return false;
        }

        try {
            $sql = "UPDATE donations SET
                        donor_name = :donor_name,
                        donor_email = :donor_email,
                        amount = :amount,
                        cause = :cause
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':donor_name', $donor_name, PDO::PARAM_STR);
            $stmt->bindParam(':donor_email', $donor_email, PDO::PARAM_STR);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':cause', $cause, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Modèle Donation - Erreur PDO dans update() (ID: " . (int)$id . "): " . $e->getMessage() . " | Données: " . print_r($data, true));
            if ($e->getCode() == 23000) {
                 error_log("Modèle Donation - Tentative de mise à jour violant une contrainte d'unicité (ID: " . (int)$id . ").");
            }
            return false;
        }
    }

    /**
     * Supprime un don.
     *
     * @param int $id ID du don à supprimer.
     * @return bool True si succès (1 ligne affectée), false sinon.
     */
    public function delete($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            error_log("Modèle Donation - delete: ID invalide fourni (valeur: '" . htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') . "').");
            return false;
        }

        try {
            $sql = "DELETE FROM donations WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $success = $stmt->execute();
            
            return ($success && $stmt->rowCount() === 1);
        } catch (PDOException $e) {
            error_log("Modèle Donation - Erreur PDO dans delete() (ID: " . (int)$id . "): " . $e->getMessage());
            return false;
        }
    }
} // Fin de la classe Donation
?>