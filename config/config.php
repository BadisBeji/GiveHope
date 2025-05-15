<?php
class Database {
    private static $pdo = null;

    public static function connect() {
        if (!isset(self::$pdo)) {
            $host = 'localhost';
            $dbname = 'givehope';
            $user = 'root';
            $pass = '';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return self::$pdo;
            } catch (PDOException $e) {
                die("❌ Erreur de connexion : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
