<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/User.php';

$pdo       = Database::connect();
$userModel = new User($pdo);
$users     = $userModel->getAllUsers();
?>

<h2>👤 Liste des utilisateurs</h2>

<table border="1" cellpadding="8" cellspacing="0"
       style="width:100%; background:#fff; border-collapse:collapse;">
  <thead style="background-color:#1abc9c; color:#fff;">
    <tr>
      <th>ID</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>CIN</th>
      <th>Email</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= htmlspecialchars($u['id']) ?></td>
        <td><?= htmlspecialchars($u['nom']) ?></td>
        <td><?= htmlspecialchars($u['prenom']) ?></td>
        <td><?= htmlspecialchars($u['cin']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td>
          <a href="/givehope-master/index.php?action=edit&id=<?= $u['id'] ?>">✏️ Modifier</a> |
          <a href="/givehope-master/index.php?action=delete&id=<?= $u['id'] ?>"
             onclick="return confirm('❌ Supprimer cet utilisateur ?')">🗑️ Supprimer</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
