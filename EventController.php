<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Event.php';


class EventController
{
    
    private $db;
    public function connect() {
        // Example connection code
        $this->db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function listEvents()
    {
        $sql = "SELECT * FROM events ORDER BY start_datetime DESC";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            $events = $liste->fetchAll(PDO::FETCH_ASSOC);
            return $events;
        } catch (Exception $e) {
            error_log('Erreur dans listEvents: ' . $e->getMessage());
            throw new Exception('Erreur lors de la récupération des événements: ' . $e->getMessage());
        }
    }

    public function deleteEvent($id)
    {
        $sql = "DELETE FROM events WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            error_log('Erreur dans deleteEvent: ' . $e->getMessage());
            throw new Exception('Erreur lors de la suppression de l\'événement: ' . $e->getMessage());
        }
    }

    public function addEvent($event)
    {
        $sql = "INSERT INTO events (name, description, start_datetime, end_datetime, location, category, organizer, status) 
                VALUES (:name, :description, :start_datetime, :end_datetime, :location, :category, :organizer, :status)";
        $db = config::getConnexion();
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
            ]);
            return true;
        } catch (Exception $e) {
            error_log('Erreur dans addEvent: ' . $e->getMessage());
            throw new Exception('Erreur lors de l\'ajout de l\'événement: ' . $e->getMessage());
        }
    }

    public function updateEvent($event, $id) {
    try {
        $db = config::getConnexion();
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
            'id' => $id,
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
        throw new Exception('Erreur lors de la mise à jour de l\'événement: ' . $e->getMessage());
    }
}

    public function showEvent($id)
    {
        $sql = "SELECT * FROM events WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();

            $event = $query->fetch(PDO::FETCH_ASSOC);
            if (!$event) {
                throw new Exception('Événement non trouvé');
            }
            return $event;
        } catch (Exception $e) {
            error_log('Erreur dans showEvent: ' . $e->getMessage());
            throw new Exception('Erreur lors de la récupération de l\'événement: ' . $e->getMessage());
        }
    }
    public function getEventById($id) {
        error_log("Tentative de récupération de l'événement avec ID: " . $id); // Pour le débogage
        $conn = $this->connect(); // Utilise ta fonction de connexion existante
        $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}