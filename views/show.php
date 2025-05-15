<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'Association</title>
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Styles de la sidebar */
      /* Sidebar */
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

        /* Styles principaux */
        body {
            margin-left: 250px;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Overpass', sans-serif;
        }

        .details-container {
            flex: 1;
            padding: 40px;
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #1a237e;
            border-bottom: 3px solid #1a237e;
            padding-bottom: 15px;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .details-box p {
            font-size: 16px;
            margin: 15px 0;
            padding: 15px;
            background: #f8f9ff;
            border-left: 4px solid #2196f3;
            border-radius: 6px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            background: #1a237e;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 30px;
            transition: 0.3s;
        }

        .back-button:hover {
            background: #2196f3;
            transform: translateY(-2px);
        }

        /* Footer */
        footer {
            background: #1a237e;
            color: white;
            padding: 25px;
            text-align: center;
            margin-top: auto;
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

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                width: 70px;
            }
            
            #sidebar ul li a span {
                display: none;
            }
            
            body {
                margin-left: 70px;
            }
            
            .details-container {
                margin: 20px;
                padding: 20px;
            }
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


    <!-- Contenu principal -->
    <div class="details-container">
        <h2>📄 Détails de l'Association</h2>
        
        <div class="details-box">
            <p><strong>Nom:</strong> <?= htmlspecialchars($assoc['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($assoc['email']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($assoc['description']) ?></p>
            <p><strong>Adresse:</strong> <?= htmlspecialchars($assoc['address']) ?></p>
            <p><strong>Téléphone:</strong> <?= htmlspecialchars($assoc['phone']) ?></p>
            <p><strong>Domaine:</strong> <?= htmlspecialchars($assoc['domain']) ?></p>
            <p><strong>Date de création:</strong> <?= htmlspecialchars($assoc['creation_date']) ?></p>
            <p><strong>Pays:</strong> <?= htmlspecialchars($assoc['country']) ?></p>
            
            <a href="index.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2025 Give4You. Tous droits réservés.</p>
        <div style="margin-top: 15px;">
            <a href="#">Mentions légales</a>
            <a href="#">Confidentialité</a>
            <a href="#">Contact</a>
        </div>
    </footer>

</body>
</html>