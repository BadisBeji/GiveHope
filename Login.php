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
            // Connexion à la base de données
            $conn = new mysqli("localhost", "root", "", "givehope");
            if ($conn->connect_error) {
                die("❌ Échec de la connexion : " . $conn->connect_error);
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            // Requête utilisateur
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if ((int)$user['bloque'] === 1) {
                    header("Location: login.html?error=" . urlencode("⚠️ Utilisateur bloqué. Vous avez violé les règles et la mission de Give4You."));
                    exit;
                } elseif (password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: " . ($user['role'] === 'admin' ? 'dashboard.html' : 'index.html'));
                    exit;
                } else {
                    $message = "❌ Mot de passe incorrect.";
                }
            } else {
                $message = "❌ Utilisateur non trouvé.";
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!-- === PARTIE HTML LOGIN (exemple rapide) === -->
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
