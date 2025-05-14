    <?php
    class Database {
        private static $pdo = null;

        public static function connect() {
            if (self::$pdo === null) {
                try {
                    $host = 'localhost';
                    $dbname = 'givehope';
                    $username = 'root';
                    $password = '';

                    self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Erreur de connexion : " . $e->getMessage());
                }
            }
            return self::$pdo;
        }
    }
    ?>
