<form method="POST" action="index.php?action=edit">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    <input type="text" name="nom" value="<?= $user['nom'] ?>" required>
    <input type="text" name="prenom" value="<?= $user['prenom'] ?>" required>
    <input type="text" name="cin" value="<?= $user['cin'] ?>" required>
    <input type="email" name="email" value="<?= $user['email'] ?>" required>
    <input type="password" name="password" placeholder="Nouveau mot de passe" required>
    <input type="submit" value="Mettre à jour">
</form>