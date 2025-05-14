    <?php
    // Emplacement: givehope-master/config/database.php

    /**
     * Fichier de Configuration de la Base de Données.
     *
     * Définit les constantes PHP utilisées par la classe Database (app/core/Database.php)
     * pour établir une connexion à la base de données MySQL via PDO.
     *
     * ATTENTION : Assurez-vous que ces valeurs correspondent à la configuration de votre serveur MySQL local (XAMPP).
     * En production, ces identifiants ne devraient pas être stockés directement dans le code versionné.
     */

    // 1. Hôte de la base de données (Database Host)
    if (!defined('DB_HOST')) {
        define('DB_HOST', 'localhost');
    }

    // 2. Nom d'utilisateur de la base de données (Database User)
    if (!defined('DB_USER')) {
        define('DB_USER', 'root');
    }

    // 3. Mot de passe de l'utilisateur de la base de données (Database Password)
    if (!defined('DB_PASS')) {
        define('DB_PASS', ''); // Laissez vide si root n'a pas de mot de passe par défaut sur XAMPP
    }

    // 4. Nom de la base de données (Database Name)
    // MODIFIÉ ICI pour utiliser "givehope"
    if (!defined('DB_NAME')) {
        define('DB_NAME', 'givehope'); // <<< CHANGEMENT ICI
    }

    // 5. Jeu de caractères pour la connexion PDO (Database Charset)
    if (!defined('DB_CHARSET')) {
        define('DB_CHARSET', 'utf8mb4');
    }

    ?>
    