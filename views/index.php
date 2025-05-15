<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Give4You — Liste des Associations</title>

  <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/style.css">

<style>
   #sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background-color: rgb(25, 34, 74);
    color: white;
    padding-top: 30px;
    border-right: 1px solid #ccc;
    animation: slideInLeft 0.5s ease-out;
  }

        #sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.2s;
        }

        #sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        #sidebar ul li a i {
            width: 25px;
            text-align: center;
        }
  /* Container (Contenu principal) */
  .container {
    margin-left: 500px; /* largeur de la sidebar */
    padding: 30px;
    width: 100%;
    min-height: 100vh;
    background: #ffffff;
    box-sizing: border-box;
    animation: fadeIn 1s ease-in;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  /* Table stylisée */
  .styled-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
    margin-top: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    table-layout: fixed;
  }

  .styled-table th,
  .styled-table td {
    padding: 12px 15px;
    text-align: left;
    word-wrap: break-word;
    transition: background-color 0.3s ease;
  }

  .styled-table thead tr {
    background-color: #3498db;
    color: white;
  }

  .styled-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  .styled-table tbody tr:hover {
    background-color: #dfeffc;
  }

  /* Boutons */
  .btn-add,
  .btn-view,
  .btn-edit,
  .btn-delete {
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: bold;
    color: white;
    text-decoration: none;
    display: inline-block;
    transition: transform 0.2s ease;
  }

  .btn-add { background-color: #2ecc71; }
  .btn-view { background-color: #2980b9; }
  .btn-edit { background-color: #f39c12; }
  .btn-delete { background-color: #e74c3c; }

  .btn-add:hover,
  .btn-view:hover,
  .btn-edit:hover,
  .btn-delete:hover {
    transform: scale(1.05);
  }

  /* Barre du haut */
  .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
  }

  /* Formulaire de recherche */
  .search-form input[type="text"] {
    padding: 6px;
    border-radius: 4px;
    border: 1px solid #ccc;
    transition: box-shadow 0.3s;
  }

  .search-form input[type="text"]:focus {
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
    outline: none;
  }

  /* Pagination */
  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .pagination button {
    margin: 0 5px;
    padding: 5px 10px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .pagination button:hover {
    background-color: #2c3e50;
  }

  .pagination button.active {
    background: #2c3e50;
  }

  /* Footer */
  footer {
    background-color: rgb(25, 34, 74);
    color: white;
    padding: 20px 0;
    text-align: center;
    position: relative;
    bottom: 0;
    width: 100%;
  }

  footer a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
  }

  footer a:hover {
    text-decoration: underline;
  }
</style>

</head>
<body>

  <!-- Sidebar -->
  <div id="sidebar">
    <ul>
      <li><a href="#">Dashboard</a></li>
      <li><a href="index.php">Liste des Associations</a></li>
      <li><a href="#">Gestion des Membres</a></li>
      <li><a href="#">Paramètres</a></li>
      <li><a href="#">Rapports</a></li>
      <li><a href="#">Historique</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="container">
    <h2>Liste des Associations</h2>

    <div class="top-bar">
      <a href="index.php?action=create" class="btn-add">+ Ajouter une association</a>
      <a href="index.php?action=statistics" class="btn-add">Statistiques</a>
      <form class="search-form" onsubmit="return false;">
        <input type="text" id="searchInput" placeholder="Rechercher par pays...">
      </form>
    </div>

    <table class="styled-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Pays</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="associationTable">
        <?php while ($row = $data->fetch(PDO::FETCH_ASSOC)) : ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['country']) ?></td>
         <!-- Modification dans le HTML -->
<td>
  <div class="btn-group">
    <a href="index.php?action=show&id=<?= $row['id'] ?>" 
       class="btn-view" 
       style="width: 100%;">
      Voir
    </a>
    <a href="index.php?action=edit&id=<?= $row['id'] ?>" 
       class="btn-edit" 
       style="width: 100%;">
      Modifier
    </a>
    <a href="index.php?action=delete&id=<?= $row['id'] ?>" 
       class="btn-delete" 
       style="width: 100%;"
       onclick="return confirm('Supprimer ?')">
      Supprimer
    </a>
  </div>
</td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="pagination" id="pagination"></div>
  </div>

  <script>
    const rowsPerPage = 5;
    const table = document.getElementById("associationTable");
    const rows = Array.from(table.rows);
    const pagination = document.getElementById("pagination");

    function displayRows(start, end) {
      rows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? "" : "none";
      });
    }

    function setupPagination() {
      pagination.innerHTML = "";
      const pageCount = Math.ceil(rows.length / rowsPerPage);
      for (let i = 0; i < pageCount; i++) {
        const btn = document.createElement("button");
        btn.textContent = i + 1;
        btn.addEventListener("click", () => {
          document.querySelectorAll("#pagination button").forEach(b => b.classList.remove("active"));
          btn.classList.add("active");
          displayRows(i * rowsPerPage, (i + 1) * rowsPerPage);
        });
        if (i === 0) btn.classList.add("active");
        pagination.appendChild(btn);
      }
    }

    function filterRows() {
      const input = document.getElementById("searchInput").value.toLowerCase();
      const filteredRows = rows.filter(row =>
        row.cells[2].textContent.toLowerCase().includes(input)
      );

      table.innerHTML = "";
      filteredRows.forEach(row => table.appendChild(row));
      setupPagination();
      displayRows(0, rowsPerPage);
    }

    document.getElementById("searchInput").addEventListener("keyup", filterRows);

    // Initial setup
    setupPagination();
    displayRows(0, rowsPerPage);
  </script>
  <!-- Footer Section -->
  <footer>
    <p>&copy; 2025 Give4You. Tous droits réservés.</p>
    <p>
      <a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a> | <a href="#">Conditions d'utilisation</a>
    </p>
  </footer>
</body>
</html>