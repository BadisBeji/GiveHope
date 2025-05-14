<?php
// Emplacement: givehope-master/app/core/Database.php

/**
 * Classe Database
 * Gère la connexion à la base de données en utilisant PDO (PHP Data Objects).
 * Implémente le design pattern Singleton pour s'assurer qu'une seule instance de connexion
 * à la base de données est créée et partagée à travers l'application.
 *
 * Dépendances :
 * - Constantes de configuration de la base de données définies dans config/database.php
 */
class Database
{
    private static $instance = null;
    private $conn;

    /**
     * Constructeur privé pour empêcher l'instanciation directe (Singleton).
     * Établit la connexion à la base de données.
     *
     * @throws PDOException Si la tentative de connexion échoue.
     */
    private function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        // Optionnel: Ajouter le port si défini et différent du port par défaut
        // if (defined('DB_PORT') && DB_PORT !== '3306') {
        //     $dsn .= ';port=' . DB_PORT;
        // }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("ERREUR DE CONNEXION PDO: " . $e->getMessage() . " (Code: " . $e->getCode() . ")");
            // En production, ne pas afficher les détails de l'exception.
            // Lever une exception plus générique ou afficher un message d'erreur contrôlé.
            throw new PDOException("Impossible d'établir la connexion à la base de données. Détails techniques loggués.", (int)$e->getCode(), $e);
        }
    }

    /**
     * Méthode statique publique pour obtenir l'unique instance de la connexion PDO.
     *
     * @return PDO L'objet de connexion PDO.
     * @throws PDOException Si la connexion échoue lors de la première création de l'instance.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            // Le constructeur est appelé uniquement lors de la première demande d'instance.
            // L'objet Database complet est stocké dans self::$instance.
            self::$instance = new Database();
        }
        // Retourner l'objet de connexion PDO ($conn) de l'instance unique de Database.
        return self::$instance->conn;
    }

    /**
     * Empêche le clonage de l'instance (Singleton).
     */
    private function __clone()
    {
    }

    /**
     * Empêche la désérialisation de l'instance (Singleton).
     */
    public function __wakeup()
    {
        // throw new Exception("La désérialisation de la classe Database n'est pas autorisée.");
    }
}
?>