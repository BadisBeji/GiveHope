<?php
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=givehope;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("❌ Erreur de connexion : " . $e->getMessage());
}

if (isset($_GET['id']) && isset($_GET['bloque'])) {
    $id = intval($_GET['id']);
    $bloque = intval($_GET['bloque']);

    $stmt = $pdo->prepare("UPDATE users SET bloque = ? WHERE id = ?");
    $stmt->execute([$bloque, $id]);

    header("Location: ../views/users.php"); // Redirige vers la liste des utilisateurs
    exit;
} else {
    echo "❌ Requête invalide.";
}
?>
