<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=givehope;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['cin']) && !empty($_POST['email']) && !empty($_POST['password'])) {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $cin = $_POST['cin'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                // Email existe déjà
                header("Location: ../signup.html?error=email");
                exit();
            } else {
                // Hasher mot de passe
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insérer dans DB
                $insert = $pdo->prepare("INSERT INTO users (nom, prenom, cin, email, password) VALUES (?, ?, ?, ?, ?)");
                $insert->execute([$nom, $prenom, $cin, $email, $hashed_password]);

                // ✅ Redirection vers login
                header("Location: ../Login.html?success=1");
                exit();
            }
        } else {
            header("Location: ../signup.html?error=missing");
            exit();
        }

    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}
?>
