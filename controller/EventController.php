<?php
// Emplacement: C:\xampp\htdocs\givehope_project2\controllers\EventController.php

// 1. Chemin vers config.php:
// Si votre fichier config.php (qui définit class Database) est à la racine : givehope_project2/config.php
// Alors __DIR__ . '/../config.php' est correct.
// Si config.php est dans givehope_project2/config/config.php, alors ce devrait être:
// require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config.php'; // Assurez-vous que ce chemin mène à votre fichier contenant "class Database"

// 2. Chemin vers Event.php (Modèle):
// Si Event.php est dans givehope_project2/models/Event.php
// Alors __DIR__ . '/../models/Event.php' est correct.
// Si Event.php est dans givehope_project2/app/models/Event.php, alors ce serait:
// require_once __DIR__ . '/../app/models/Event.php';
require_once __DIR__ . '/../models/Event.php'; // Assurez-vous que ce chemin est correct.

class EventController
{
    private $pdo; // Propriété pour stocker la connexion PDO

    public function __construct() {
        $database = new Database(); // Utilise la classe Database de votre config.php
        $this->pdo = $database->getConnection();

        if (!$this->pdo) {
            // Gérer l'échec de connexion (log, exception, etc.)
            // error_log("Échec de la connexion à la base de données dans EventController.");
            throw new Exception("Échec de la connexion à la base de données.");
        }
    }
    
    public function listEvents()
    {
        $sql = "SELECT * FROM events ORDER BY start_datetime DESC";
        $db = $this->pdo; // Utilise la connexion établie dans le constructeur
        try {
            $liste = $db->query($sql);
            $events = $liste->fetchAll(PDO::FETCH_ASSOC);
            return $events;
        } catch (Exception $e) {
            error_log('Erreur dans listEvents: ' . $e->getMessage());
            // Ne pas afficher directement $e->getMessage() en production pour des raisons de sécurité.
            throw new Exception('Erreur lors de la récupération des événements.');
        }
    }

    public function deleteEvent($id)
    {
        $sql = "DELETE FROM events WHERE id = :id";
        $db = $this->pdo; // Utilise la connexion établie dans le constructeur
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            error_log('Erreur dans deleteEvent: ' . $e->getMessage());
            throw new Exception('Erreur lors de la suppression de l\'événement.');
        }
    }

    public function addEvent(Event $event) // Utilisation du typage d'objet Event si votre modèle Event est une classe
    {
        $sql = "INSERT INTO events (name, description, start_datetime, end_datetime, location, category, organizer, status) 
                VALUES (:name, :description, :start_datetime, :end_datetime, :location, :category, :organizer, :status)";
        $db = $this->pdo; // Utilise la connexion établie dans le constructeur
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'name' => $event->getName(),
                'description' => $event->getDescription(),
                'start_datetime' => $event->getStartDatetime()->format('Y-m-d H:i:s'), 
                'end_datetime' => $event->getEndDatetime()->format('Y-m-d H:i:s'),
                'location' => $event->getLocation(),
                'category' => $event->getCategory(),
                'organizer' => $event->getOrganizer(),
                'status' => $event->getStatus()
                // Assurez-vous que votre objet Event a toutes ces méthodes getName(), getDescription(), etc.
            ]);
            return true;
        } catch (Exception $e) {
            error_log('Erreur dans addEvent: ' . $e->getMessage());
            throw new Exception('Erreur lors de l\'ajout de l\'événement.');
        }
    }

    public function updateEvent(Event $event, $id) { // Utilisation du typage d'objet Event
        $db = $this->pdo; // Utilise la connexion établie dans le constructeur
        try {
            $query = $db->prepare(
                'UPDATE events SET 
                    name = :name,
                    description = :description,
                    start_datetime = :start_datetime,
                    end_datetime = :end_datetime,
                    location = :location,
                    category = :category,
                    organizer = :organizer,
                    status = :status
                WHERE id = :id'
            );

            $query->execute([
                'id' => $id, // Assurez-vous que l'ID est bien passé et utilisé
                'name' => $event->getName(),
                'description' => $event->getDescription(),
                'start_datetime' => $event->getStartDatetime()->format('Y-m-d H:i:s'), 
                'end_datetime' => $event->getEndDatetime()->format('Y-m-d H:i:s'),
                'location' => $event->getLocation(),
                'category' => $event->getCategory(),
                'organizer' => $event->getOrganizer(),
                'status' => $event->getStatus()
            ]);

            return true;
        } catch (PDOException $e) {
            error_log('Erreur dans updateEvent: ' . $e->getMessage());
            throw new Exception('Erreur lors de la mise à jour de l\'événement.');
        }
    }

    public function showEvent($id)
    {
        $sql = "SELECT * FROM events WHERE id = :id";
        $db = $this->pdo; // Utilise la connexion établie dans le constructeur
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();

            $eventData = $query->fetch(PDO::FETCH_ASSOC);
            if (!$eventData) {
                return null; // Ou lever une exception si l'événement doit exister
            }
            // Optionnel: Si vous avez une classe Event, hydratez et retournez un objet Event.
            // $eventObj = new Event(); 
            // $eventObj->setId($eventData['id']); ... etc.
            // return $eventObj;
            return $eventData;
        } catch (Exception $e) {
            error_log('Erreur dans showEvent: ' . $e->getMessage());
            throw new Exception('Erreur lors de la récupération de l\'événement.');
        }
    }
    
    public function getEventById($id) {
        // Cette méthode semble redondante par rapport à showEvent, mais je la corrige pour utiliser $this->pdo
        error_log("Tentative de récupération de l'événement avec ID: " . $id);
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}