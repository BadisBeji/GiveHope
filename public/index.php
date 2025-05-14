<?php
// Emplacement: givehope-master/public/index.php

/**
 * Contrôleur Frontal (Front Controller) / Routeur Principal de l'Application GiveHope MVC.
 *
 * Rôle :
 * - Point d'entrée unique pour toutes les requêtes HTTP adressées à l'application.
 * - Initialise l'environnement de l'application (ex: démarrage des sessions).
 * - Charge les fichiers de configuration et les classes "core" (fondamentales).
 * - Analyse l'URL (via les paramètres GET) pour déterminer quel contrôleur et quelle action (méthode) appeler.
 * - Instancie le contrôleur approprié et exécute l'action demandée.
 * - Gère les erreurs de base telles que contrôleur ou action non trouvé, et intercepte les exceptions critiques.
 */

// --- Initialisation de l'Application ---

// 1. Démarrer la session PHP si elle n'est pas déjà active.
//    Les sessions sont utilisées pour les messages flash (succès/erreur après redirection),
//    et pour conserver les données de formulaire en cas d'erreur de validation.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Définir une constante pour le chemin racine de l'application (utile pour les inclusions).
//    Cela rend les chemins d'inclusion plus robustes.
//    __FILE__ est le chemin complet vers ce fichier (public/index.php).
//    dirname(__FILE__) est le dossier de ce fichier (public/).
//    dirname(dirname(__FILE__)) est le dossier parent de public/ (donc givehope-master/).
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(__FILE__))); // Pointe vers G:\XXAMP\htdocs\givehope-master\
}

// 3. Charger les fichiers de configuration et les classes Core.
//    Utilisation de APPROOT pour des chemins absolus et plus fiables.

// Charger la configuration de la base de données.
// S'attend à trouver : G:\XXAMP\htdocs\givehope-master\config\database.php
$configFile = APPROOT . '/config/database.php';
if (file_exists($configFile)) {
    require_once $configFile;
} else {
    error_log("ERREUR CRITIQUE: Fichier de configuration de la base de données introuvable à " . $configFile);
    die("ERREUR CRITIQUE: Le fichier de configuration de la base de données est introuvable. L'application ne peut pas continuer. Vérifiez le chemin : " . htmlspecialchars($configFile));
}

// Charger la classe Database Core.
// S'attend à trouver : G:\XXAMP\htdocs\givehope-master\app\core\Database.php
$databaseFile = APPROOT . '/app/core/Database.php';
if (file_exists($databaseFile)) {
    require_once $databaseFile;
} else {
    error_log("ERREUR CRITIQUE: Fichier de la classe Database introuvable à " . $databaseFile);
    die("ERREUR CRITIQUE: Le fichier de la classe Database est introuvable. L'application ne peut pas continuer. Vérifiez le chemin : " . htmlspecialchars($databaseFile));
}

// Charger la classe Controller Core (classe de base pour tous les contrôleurs).
// S'attend à trouver : G:\XXAMP\htdocs\givehope-master\app\core\Controller.php
$controllerCoreFile = APPROOT . '/app/core/Controller.php';
if (file_exists($controllerCoreFile)) {
    require_once $controllerCoreFile;
} else {
    error_log("ERREUR CRITIQUE: Fichier de la classe Controller de base introuvable à " . $controllerCoreFile);
    die("ERREUR CRITIQUE: Le fichier de la classe Controller de base est introuvable. L'application ne peut pas continuer. Vérifiez le chemin : " . htmlspecialchars($controllerCoreFile));
}


// --- Routage Simple Basé sur les Paramètres GET ---
// L'URL typique sera: index.php?controller=nomDuControleur&action=nomDeLAction&id=123

// 4. Déterminer le nom du contrôleur à charger.
$controllerNameParam = $_GET['controller'] ?? 'donation'; // Contrôleur par défaut.
$controllerNameSanitized = preg_replace('/[^a-zA-Z0-9_]/', '', $controllerNameParam);
if (empty($controllerNameSanitized)) { $controllerNameSanitized = 'donation'; }

// 5. Déterminer le nom de l'action (méthode) à appeler.
$actionNameParam = $_GET['action'] ?? 'index'; // Action par défaut.
$actionNameSanitized = preg_replace('/[^a-zA-Z0-9_]/', '', $actionNameParam);
if (empty($actionNameSanitized)) { $actionNameSanitized = 'index'; }

// 6. Construire le nom complet de la classe du contrôleur et le chemin vers son fichier.
$controllerClassName = ucfirst(strtolower($controllerNameSanitized)) . 'Controller';
$controllerSpecificFile = APPROOT . '/app/controllers/' . $controllerClassName . '.php';


// --- Chargement et Exécution du Contrôleur et de l'Action ---

if (file_exists($controllerSpecificFile)) {
    require_once $controllerSpecificFile;

    if (class_exists($controllerClassName)) {
        try {
            $controllerInstance = new $controllerClassName();

            if (method_exists($controllerInstance, $actionNameSanitized)) {
                $controllerInstance->$actionNameSanitized();
            } else {
                $errorMessage = "Action non trouvée : La méthode '" . htmlspecialchars($actionNameSanitized, ENT_QUOTES, 'UTF-8') . "()' n'existe pas dans le contrôleur '" . htmlspecialchars($controllerClassName, ENT_QUOTES, 'UTF-8') . "'.";
                error_log($errorMessage . " URL: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
                http_response_code(404);
                // En production, vous auriez une vue dédiée pour les erreurs 404.
                echo "<h1>404 Page Introuvable</h1><p>Désolé, la ressource demandée (action) est introuvable.</p>";
            }
        } catch (PDOException $e) {
            // Erreur spécifique à la base de données (ex: connexion impossible, requête échouée)
            $errorMessage = "ERREUR FATALE DE BASE DE DONNÉES : " . $e->getMessage() . " (Code: " . $e->getCode() . ")";
            error_log($errorMessage . " Trace: " . $e->getTraceAsString());
            http_response_code(503); // Service Unavailable
            echo "<h1>Erreur Serveur</h1><p>Une erreur critique est survenue lors de la communication avec la base de données. Veuillez réessayer plus tard.</p>";
        } catch (Exception $e) {
            // Toute autre exception non capturée spécifiquement
            $errorMessage = "EXCEPTION FATALE NON CAPTURÉE : " . $e->getMessage() . " (Type: " . get_class($e) . ")";
            error_log($errorMessage . " Trace: " . $e->getTraceAsString());
            http_response_code(500); // Internal Server Error
            echo "<h1>Erreur Serveur</h1><p>Une erreur inattendue s'est produite au sein de l'application. Veuillez réessayer.</p>";
        }
    } else {
        $errorMessage = "Classe contrôleur non trouvée : La classe '" . htmlspecialchars($controllerClassName, ENT_QUOTES, 'UTF-8') . "' n'est pas définie dans le fichier '" . htmlspecialchars($controllerSpecificFile, ENT_QUOTES, 'UTF-8') . "'. Vérifiez le nom de la classe et du fichier.";
        error_log($errorMessage . " URL: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
        http_response_code(404);
        echo "<h1>404 Page Introuvable</h1><p>Le module demandé est introuvable (erreur de configuration interne du contrôleur).</p>";
    }
} else {
    $errorMessage = "Fichier contrôleur non trouvé : Le fichier pour le contrôleur '" . htmlspecialchars($controllerClassName, ENT_QUOTES, 'UTF-8') . "' est introuvable à l'emplacement attendu: " . htmlspecialchars($controllerSpecificFile, ENT_QUOTES, 'UTF-8');
    error_log($errorMessage . " URL: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
    http_response_code(404);
    echo "<h1>404 Page Introuvable</h1><p>Désolé, le module demandé ('" . htmlspecialchars($controllerNameSanitized, ENT_QUOTES, 'UTF-8') . "') est introuvable.</p>";
}

?>
```