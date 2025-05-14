<?php
// Emplacement: givehope-master/app/core/Controller.php

/**
 * Classe Controller de Base (Core Controller)
 *
 * Fournit des fonctionnalités communes à tous les contrôleurs de l'application.
 * Les contrôleurs spécifiques (ex: DonationController) doivent hériter de cette classe.
 */
abstract class Controller
{
    /**
     * Charge et instancie un modèle spécifié.
     *
     * @param string $model Le nom du modèle à charger (ex: 'Donation').
     * @return object Instance du modèle chargé.
     * Provoque une erreur fatale si le modèle n'est pas trouvé ou ne peut être instancié.
     */
    public function model($model)
    {
        $modelName = ucfirst(strtolower(trim($model)));
        // Utilisation de APPROOT (défini dans public/index.php) pour un chemin absolu plus fiable.
        $modelPath = APPROOT . '/app/models/' . $modelName . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;

            if (class_exists($modelName)) {
                try {
                    return new $modelName();
                } catch (PDOException $e) {
                    $errorMessage = "ERREUR PDO lors de l'instanciation du modèle '$modelName': " . $e->getMessage();
                    error_log($errorMessage);
                    die("ERREUR CRITIQUE D'APPLICATION : Problème de base de données lors du chargement du module '" . htmlspecialchars($modelName, ENT_QUOTES, 'UTF-8') . "'. (Code: CTRL_MODEL_PDO_EX)");
                } catch (Exception $e) {
                    $errorMessage = "Erreur générale lors de l'instanciation du modèle '$modelName': " . $e->getMessage();
                    error_log($errorMessage);
                    die("ERREUR CRITIQUE D'APPLICATION : Impossible de charger le module '" . htmlspecialchars($modelName, ENT_QUOTES, 'UTF-8') . "'. (Code: CTRL_MODEL_EX)");
                }
            } else {
                $errorMessage = "Classe modèle non trouvée : La classe '" . htmlspecialchars($modelName, ENT_QUOTES, 'UTF-8') . "' n'est pas définie dans '" . htmlspecialchars($modelPath, ENT_QUOTES, 'UTF-8') . "'.";
                error_log($errorMessage);
                die("ERREUR CRITIQUE D'APPLICATION : Composant modèle corrompu ('" . htmlspecialchars($modelName, ENT_QUOTES, 'UTF-8') . "'). (Code: CTRL_MODEL_CLASS_NF)");
            }
        }
        $errorMessage = "Fichier modèle non trouvé : Le fichier '" . htmlspecialchars($modelPath, ENT_QUOTES, 'UTF-8') . "' pour le modèle '" . htmlspecialchars($modelName, ENT_QUOTES, 'UTF-8') . "' n'existe pas.";
        error_log($errorMessage);
        die("ERREUR CRITIQUE D'APPLICATION : Composant modèle manquant ('" . htmlspecialchars($modelName, ENT_QUOTES, 'UTF-8') . "'). (Code: CTRL_MODEL_FILE_NF)");
    }

    /**
     * Charge (rend) un fichier de vue et lui transmet des données.
     *
     * @param string $view Le chemin de la vue relatif à 'app/views/' (ex: 'donation/form' ou 'layouts/main').
     * @param array $data Tableau associatif des données à rendre disponibles dans la vue.
     * @return void
     * Provoque une erreur fatale si le fichier de vue n'est pas trouvé.
     */
    public function view($view, $data = [])
    {
        // Utilisation de APPROOT pour un chemin absolu plus fiable.
        $viewPath = APPROOT . '/app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            extract($data); // Rend les clés du tableau $data accessibles comme variables dans la vue.
            require_once $viewPath; // Utiliser require_once est plus sûr pour les layouts.
        } else {
            $errorMessage = "Fichier de vue non trouvé : Le fichier '" . htmlspecialchars($viewPath, ENT_QUOTES, 'UTF-8') . "' pour la vue '" . htmlspecialchars($view, ENT_QUOTES, 'UTF-8') . "' n'existe pas.";
            error_log($errorMessage);
            die('ERREUR CRITIQUE D\'APPLICATION : Vue introuvable. (' . htmlspecialchars($view, ENT_QUOTES, 'UTF-8') . ') (Code: CTRL_VIEW_NF)');
        }
    }

   /**
    * Effectue une redirection HTTP vers une autre URL.
    *
    * @param string $url URL relative au point d'entrée de l'application (public/index.php).
    * @return void Termine l'exécution du script.
    */
   protected function redirect($url)
   {
        if (headers_sent($file, $line)) {
            error_log("Erreur de redirection : En-têtes déjà envoyés. Impossible de rediriger vers '$url'. Sortie dans '$file' ligne '$line'.");
            echo "<p>Redirection en cours... Si vous n'êtes pas redirigé, veuillez <a href='" . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . "'>cliquer ici</a>.</p>";
            echo "<script type='text/javascript'>window.location.href='" . addslashes($url) . "';</script>";
            echo "<noscript><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . "'></noscript>";
            exit;
        }
        header('Location: ' . $url);
        exit;
   }
}
?>