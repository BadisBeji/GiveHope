<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Give4You - Statistiques</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,500|Dosis:400,700" rel="stylesheet">
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

        #sidebar ul li a i {
            width: 25px;
            text-align: center;
        }

        /* Styles du contenu principal */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f5f7fa;
            margin-left: 250px;
        }

        .container {
            flex: 1;
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        /* Styles du graphique */
        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #1a3c61;
            font-size: 28px;
        }

        .chart-controls {
            text-align: center;
            margin-bottom: 20px;
        }

        select {
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        canvas {
            display: block;
            max-width: 700px;
            height: 400px !important;
            margin: 30px auto;
        }

        /* Styles du tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        th, td {
            padding: 14px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #f0f4f8;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9fbfd;
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
            
            .container {
                margin: 20px;
                padding: 15px;
            }
            
            canvas {
                max-width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</head>
<body>
  <!-- Sidebar -->
  <div id="sidebar">
    <ul>
      <li><a href="">Dashboard</a></li>
      <li><a href="index.php">Liste des Associations</a></li>
      <li><a href="#">Gestion des Membres</a></li>
      <li><a href="#">Paramètres</a></li>
      <li><a href="#">Rapports</a></li>
      <li><a href="#">Historique</a></li>
    </ul>
  </div>
    <!-- Contenu principal -->
    <div class="container">
        <h1>📊 Statistiques des Associations par Pays</h1>

        <div class="chart-controls">
            <label for="chartType">Type de graphique :</label>
            <select id="chartType">
                <option value="bar" selected>Histogramme</option>
                <option value="line">Courbe</option>
            </select>
        </div>

        <canvas id="statsChart"></canvas>

        <table>
            <thead>
                <tr>
                    <th>Pays</th>
                    <th>Nombre d'Associations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['country']) ?></td>
                        <td><?= $row['total'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2025 Give4You. Tous droits réservés.</p>
        <div style="margin-top: 15px;">
            <a href="#" style="color: #fff; margin: 0 15px;">Mentions légales</a>
            <a href="#" style="color: #fff; margin: 0 15px;">Confidentialité</a>
            <a href="#" style="color: #fff; margin: 0 15px;">Contact</a>
        </div>
    </footer>

    <script>
        const labels = <?= json_encode(array_column($stats, 'country')) ?>;
        const data = <?= json_encode(array_column($stats, 'total')) ?>;

        const colors = [
            '#2196f3', '#ff9800', '#4caf50', 
            '#e91e63', '#9c27b0', '#00bcd4'
        ];

        const ctx = document.getElementById('statsChart').getContext('2d');
        let chart;

        function createChart(type) {
            chart = new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre d\'Associations',
                        data: data,
                        backgroundColor: colors,
                        borderColor: '#1a237e',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Répartition par Pays',
                            font: { size: 20 }
                        },
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }

        createChart('bar');

        document.getElementById('chartType').addEventListener('change', function() {
            const newType = this.value;
            chart.destroy();
            createChart(newType);
        });
    </script>

    <!-- Lien Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>