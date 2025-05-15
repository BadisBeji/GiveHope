<?php
session_start();

$secretKey = "6LcdmzcrAAAAAJtsi4YaiYE5qAivii1PSUMWjQ0O";
$message = "";

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification du reCAPTCHA
    if (empty($_POST['g-recaptcha-response'])) {
        $message = "⚠️ Veuillez valider le reCAPTCHA.";
    } else {
        $recaptcha = $_POST['g-recaptcha-response'];
        $verifyURL = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha";
        $response = file_get_contents($verifyURL);
        $responseKeys = json_decode($response, true);

        if (!$responseKeys["success"]) {
            $message = "⚠️ Vérification reCAPTCHA échouée.";
        } else {
            try {
                // Connexion à la base de données avec PDO
                $conn = new PDO("mysql:host=localhost;dbname=givehope", "root", "");
                // Configuration de PDO pour afficher les erreurs
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $email = $_POST['email'];
                $password = $_POST['password'];
                
                // Debug - Afficher les valeurs pour vérification
                // echo "Email: " . $email . "<br>";
                
                // Requête utilisateur - utilisation de requête préparée pour sécurité
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$email]);
                
                // Récupération de l'utilisateur
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Debug - Vérifier si l'utilisateur est trouvé
                // var_dump($user);
                
                if ($user) {
                    // Utilisateur trouvé, vérifier s'il est bloqué
                    if ((int)$user['bloque'] === 1) {
                        header("Location: login.html?error=" . urlencode("⚠️ Utilisateur bloqué. Vous avez violé les règles et la mission de Give4You."));
                        exit;
                    } 
                    // Vérification du mot de passe
                    elseif (password_verify($password, $user['password'])) {
                        // Authentification réussie
                        $_SESSION['user'] = $user;
                        
                        // Debug - Afficher la session
                        // var_dump($_SESSION);
                        
                        // Redirection en fonction du rôle
                        header("Location: " . ($user['role'] === 'admin' ? 'dashboard.html' : 'index.html'));
                        exit;
                    } else {
                        // Mot de passe incorrect
                        $message = "❌ Mot de passe incorrect.";
                        
                        // Debug - Confirmer le problème de mot de passe
                        // echo "Échec de vérification du mot de passe";
                    }
                } else {
                    // Utilisateur non trouvé
                    $message = "❌ Utilisateur non trouvé.";
                }
                
                // Fermeture des ressources
                $stmt = null;
                $conn = null;
                
            } catch (PDOException $e) {
                // Capture des erreurs de base de données
                $message = "❌ Erreur de base de données: " . $e->getMessage();
                
                // Pour le débogage uniquement (à supprimer en production)
                // echo "Erreur: " . $e->getMessage();
            }
        }
    }
}

// Debug - Afficher le message final
// echo "Message final: " . $message;
?>

<!-- === PARTIE HTML LOGIN === -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <h2>Connexion</h2>

    <?php if (!empty($message)) : ?>
        <p style="color: red;"><?= $message ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Mot de passe:</label>
        <input type="password" name="password" required><br><br>

        <!-- reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="TA_CLÉ_SITE_RECAPTCHA_ICI"></div><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>