<?php
// Emplacement: givehope-master/app/core/Controller.php

/**
 * Classe Controller de Base (Core Controller)
 *
 * Tous les contrôleurs spécifiques de l'application (par exemple, DonationController)
 * doivent hériter de cette classe. Elle fournit des fonctionnalités et des méthodes
 * communes qui sont utiles à travers différents contrôleurs, telles que :
 * - Le chargement et l'instanciation des modèles.
 * - Le chargement (rendu) des fichiers de vue, en leur passant des données.
 * - La gestion des redirections HTTP.
 *
 * Cette approche centralise la logique commune, rendant les contrôleurs spécifiques
 * plus concis et focalisés sur leur tâche unique.
 */
abstract class Controller // Marqué comme abstract car elle n'est pas censée être instanciée directement.
{
    /**
     * Charge et instancie un modèle spécifié.
     *
     * Cette méthode recherche un fichier de modèle dans le dossier 'app/models/'.
     * Le nom du fichier doit correspondre au nom du modèle avec une majuscule initiale (ex: 'Donation' -> 'Donation.php').
     * La classe à l'intérieur du fichier doit également correspondre à ce nom.
     *
     * @param string $model Le nom du modèle à charger (ex: 'Donation').
     * La convention est que le nom de la classe du modèle sera ucfirst($model).
     * @return object|null Une instance du modèle chargé si le fichier et la classe existent et que l'instanciation réussit.
     * En cas d'échec (fichier non trouvé, classe non définie, ou erreur PDO lors de l'instanciation
     * du modèle si celui-ci se connecte à la DB dans son constructeur), cette méthode
     * provoquera une erreur fatale (die). Dans un environnement de production,
     * un système de gestion d'erreurs plus sophistiqué (ex: lever une exception personnalisée
     * attrapée par le front controller) serait préférable à l'utilisation de die().
     *
     * @example $donationModel = $this->model('Donation');
     */
    public function model($model)
    {
        // Convertir la première lettre du nom du modèle en majuscule pour correspondre au nom de la classe et du fichier.
        // Par convention, les noms de classes commencent par une majuscule (CamelCase).
        $modelName = ucfirst(strtolower(trim($model))); // Assure une capitalisation cohérente, ex: "donation" ou "DONATION" devient "Donation"

        // Construire le chemin complet vers le fichier du modèle.
        $modelPath = '../app/models/' . $modelName . '.php';

        // Vérifier si le fichier du modèle existe.
        if (file_exists($modelPath)) {
            require_once $modelPath; // Inclure le fichier du modèle une seule fois.

            // Vérifier si la classe du modèle existe maintenant (après l'inclusion du fichier).
            if (class_exists($modelName)) {
                try {
                    // Instancier le modèle.
                    // Le constructeur du modèle (ex: celui de la classe Donation)
                    // est responsable de l'obtention de l'instance de la base de données si nécessaire
                    // (généralement en appelant Database::getInstance()).
                    return new $modelName();
                } catch (PDOException $e) {
                    // Intercepter spécifiquement les PDOExceptions qui pourraient être levées
                    // si le constructeur du modèle tente d'établir une connexion DB et échoue.
                    $errorMessage = "ERREUR PDO lors de l'instanciation du modèle '$modelName': " . $e->getMessage() . " (Code: " . $e->getCode() . ")";
                    error_log($errorMessage); // Logger l'erreur technique.
                    // En production, ne pas afficher $e->getMessage() directement. Afficher une page d'erreur générique.
                    die("ERREUR CRITIQUE D'APPLICATION : Impossible de charger les données nécessaires (problème de base de données lors du chargement du modèle '" . htmlspecialchars($modelName, ENT_QUOTES) . "'). Veuillez contacter l'administrateur. (Code: CTRL_MODEL_PDO_EXCEPTION)");
                } catch (Exception $e) {
                    // Intercepter d'autres types d'exceptions qui pourraient survenir lors de l'instanciation du modèle.
                    $errorMessage = "Erreur générale lors de l'instanciation du modèle '$modelName': " . $e->getMessage();
                    error_log($errorMessage);
                    die("ERREUR CRITIQUE D'APPLICATION : Impossible de charger un composant essentiel ('" . htmlspecialchars($modelName, ENT_QUOTES) . "'). (Code: CTRL_MODEL_GENERAL_EXCEPTION)");
                }
            } else {
                // Le fichier du modèle a été inclus, mais la classe attendue n'y est pas définie.
                // Cela indique généralement une erreur de nommage de la classe dans le fichier modèle.
                $errorMessage = "Classe modèle non trouvée : La classe '" . htmlspecialchars($modelName, ENT_QUOTES) . "' n'est pas définie dans le fichier '" . htmlspecialchars($modelPath, ENT_QUOTES) . "' (fichier inclus mais classe absente).";
                error_log($errorMessage);
                die("ERREUR CRITIQUE D'APPLICATION : Composant modèle corrompu ou mal nommé ('" . htmlspecialchars($modelName, ENT_QUOTES) . "'). (Code: CTRL_MODEL_CLASS_NOT_FOUND)");
            }
        }
        // Le fichier du modèle lui-même n'a pas été trouvé à l'emplacement spécifié.
        $errorMessage = "Fichier modèle non trouvé : Le fichier '" . htmlspecialchars($modelPath, ENT_QUOTES) . "' pour le modèle '" . htmlspecialchars($modelName, ENT_QUOTES) . "' n'existe pas.";
        error_log($errorMessage);
        die("ERREUR CRITIQUE D'APPLICATION : Fichier modèle essentiel manquant ('" . htmlspecialchars($modelName, ENT_QUOTES) . "'). Vérifiez le chemin et le nom du fichier. (Code: CTRL_MODEL_FILE_NOT_FOUND)");
    }

    /**
     * Charge (rend) un fichier de vue et lui transmet des données.
     * Les vues sont des fichiers PHP/HTML situés dans le dossier 'app/views/'.
     * La méthode `extract()` est utilisée pour convertir les clés d'un tableau associatif en variables
     * distinctes, qui deviennent alors accessibles directement dans le fichier de vue inclus.
     *
     * @param string $view Le chemin de la vue relatif au dossier 'app/views/' (ex: 'layouts/main' ou 'donation/form').
     * Ne pas inclure l'extension '.php'.
     * @param array $data Un tableau associatif des données à rendre disponibles dans la vue.
     * Exemple : ['titre' => 'Mon Titre', 'utilisateur' => $userObject]
     * Dans la vue, on pourra alors utiliser $titre et $utilisateur.
     * Si la vue est un layout (comme 'layouts/main'), $data contiendra généralement
     * des clés comme 'pageTitle', 'contentView' (le chemin vers la vue de contenu),
     * et 'viewData' (un autre tableau de données spécifiquement pour la 'contentView').
     * @return void
     * Provoque une erreur fatale (die) si le fichier de vue n'est pas trouvé.
     * En production, une gestion d'erreur plus élégante est recommandée.
     *
     * @example $this->view('donation/form', ['form_errors' => $errors, 'form_data' => $data]);
     * @example $this->view('layouts/main', ['pageTitle' => 'Accueil', 'contentView' => 'pages/home', 'viewData' => []]);
     */
    public function view($view, $data = [])
    {
        // Construire le chemin complet vers le fichier de la vue.
        $viewPath = '../app/views/' . $view . '.php';

        // Vérifier si le fichier de la vue existe.
        if (file_exists($viewPath)) {
            // extract() importe les variables d'un tableau dans la table des symboles actuelle.
            // Les clés du tableau $data deviennent des noms de variables, et leurs valeurs
            // deviennent les valeurs de ces variables.
            // EXTR_SKIP (optionnel) : en cas de collision, ne pas écraser une variable existante.
            // Pour la simplicité, nous n'utilisons pas de drapeau ici, mais il faut être conscient
            // des risques de collision si les clés de $data sont des noms de variables déjà utilisées.
            extract($data);

            // Inclure le fichier de la vue. Le code PHP à l'intérieur sera exécuté,
            // et le HTML sera envoyé au navigateur. Les variables extraites sont accessibles ici.
            require_once $viewPath;
        } else {
            // Le fichier de la vue n'a pas été trouvé.
            $errorMessage = "Fichier de vue non trouvé : Le fichier '" . htmlspecialchars($viewPath, ENT_QUOTES) . "' pour la vue '" . htmlspecialchars($view, ENT_QUOTES) . "' n'existe pas.";
            error_log($errorMessage); // Logger l'erreur pour le débogage.
            // En production, afficher une page d'erreur générique.
            die('ERREUR CRITIQUE D\'APPLICATION : Vue introuvable. Impossible d\'afficher la page demandée. (' . htmlspecialchars($view, ENT_QUOTES) . ') (Code: CTRL_VIEW_NOT_FOUND)');
        }
    }

   /**
    * Effectue une redirection HTTP vers une autre URL.
    * Cette méthode envoie un en-tête HTTP 'Location' et arrête ensuite l'exécution du script.
    *
    * @param string $url L'URL vers laquelle rediriger. Il s'agit généralement d'une URL relative
    * au point d'entrée de l'application (public/index.php).
    * Exemple : 'index.php?controller=donation&action=index'
    * Pour des URLs absolues, assurez-vous qu'elles sont correctement formées.
    * @return void Cette méthode ne retourne jamais car elle termine l'exécution du script avec exit().
    *
    * @example $this->redirect('index.php?controller=user&action=login');
    */
   protected function redirect($url)
   {
        // S'assurer qu'aucun contenu (HTML, espaces blancs avant <?php) n'a été envoyé au navigateur
        // avant d'appeler header(), sinon cela provoquera une erreur "headers already sent".
        if (headers_sent($file, $line)) {
            // Si les en-têtes ont déjà été envoyés, la redirection HTTP ne fonctionnera pas.
            // Logger cette erreur critique.
            error_log("Erreur de redirection : Les en-têtes HTTP ont déjà été envoyés. Impossible de rediriger vers '$url'. Sortie détectée dans '$file' à la ligne '$line'.");
            // Afficher un message de secours (ou utiliser une redirection JavaScript si possible, bien que moins fiable).
            echo "<p>Redirection en cours... Si vous n'êtes pas redirigé automatiquement, veuillez <a href='" . htmlspecialchars($url, ENT_QUOTES) . "'>cliquer ici</a>.</p>";
            // Optionnel: Redirection JavaScript comme solution de repli.
            // echo "<script type='text/javascript'>window.location.href='" . addslashes($url) . "';</script>";
            // echo "<noscript><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($url, ENT_QUOTES) . "'></noscript>";
            exit; // Arrêter quand même pour éviter l'exécution de code supplémentaire.
        }

        // Effectuer la redirection HTTP.
        header('Location: ' . $url);
        // Il est crucial d'appeler exit() immédiatement après header('Location: ...')
        // pour s'assurer que le script s'arrête et que la redirection est bien effectuée.
        exit;
   }
} // Fin de la classe Controller
?>