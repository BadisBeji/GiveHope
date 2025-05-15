<?php

class config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (self::$pdo === null) {
            $servername = "localhost";  // Utilisez "localhost" pour votre serveur local
            $username = "root";         // Par défaut, l'utilisateur est "root"
            $password = "";             // Laissez vide si vous n'avez pas défini de mot de passe
            $dbname = "givehope";     // Assurez-vous que votre base de données est bien nommée "charity_db"

            try {
                self::$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Erreur : ' . $e->getMessage());  // Affiche l'erreur en cas de problème
            }
        }
        return self::$pdo;
    }
}
$pdo = Config::getConnexion();

?>
