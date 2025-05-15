<?php
include '../../controllers/EventController.php';
$eventController = new EventController();
$list = $eventController->listEvents();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Liste des Événements - Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css" />

    <style>
        body {
            display: flex;
            min-height: 100vh;
        }

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
            z-index: 10;
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

        /* Main Content Section */
        #content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px; /* Add margin to the left to accommodate the sidebar */
        }

        #content {
            flex: 1;
            padding: 20px;
        }

        /* Page Heading */
        .d-sm-flex {
            margin-bottom: 20px;
        }

        /* Content Row */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .col-xl-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #e3e6f0;
            border-radius: 0.3rem;
        }

        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .shadow {
            box-shadow: 0 0.15rem 0.4rem 0 rgba(0, 0, 0, 0.06) !important;
        }

        .h-100 {
            height: 100% !important;
        }

        .py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .card-body {
            flex: 1 1 auto;
            padding: 1.5rem;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #858796;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #e3e6f0;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #e3e6f0;
        }

        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-warning {
            color: #fff;
            background-color: #f6c23e;
            border-color: #f6c23e;
        }

        .btn-warning:hover {
            background-color: #e5b137;
            border-color: #d4a034;
        }

        .btn-danger {
            color: #fff;
            background-color: #e74a3b;
            border-color: #e74a3b;
        }

        .btn-danger:hover {
            background-color: #d1382b;
            border-color: #c02d22;
        }

        /* Footer Section */
        footer {
            background-color: rgb(25, 34, 74);
            color: white;
            padding: 20px 0;
            text-align: center;
            position: sticky;
            bottom: 0;
            width: 100%;
            z-index: 9; /* Ensure footer is below the sidebar */
        }

        footer a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            #sidebar {
                position: static;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #ccc;
                padding-bottom: 15px;
            }

            #content-wrapper {
                margin-left: 0; /* Reset margin for smaller screens */
            }

            #content {
                padding-top: 15px;
            }
        }
    </style>
</head>
<body id="page-top">

    <div id="wrapper">

        <div id="sidebar">
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="eventList.php">Liste des évènements</a></li>
                <li><a href="addEvent.php">ajouter un évènement</a></li>
            </ul>
        </div>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Liste des Événements</h1>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom</th>
                                                    <th>Description</th>
                                                    <th>Date de Début</th>
                                                    <th>Date de Fin</th>
                                                    <th>Lieu</th>
                                                    <th>Catégorie</th>
                                                    <th>Organisateur</th>
                                                    <th>Statut</th>
                                                    <th colspan="2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($list as $event) {
                                                    $now = date('Y-m-d H:i:s');
                                                    $start = $event['start_datetime'];
                                                    $end = $event['end_datetime'];

                                                    // Calculer le statut dynamique
                                                    if (!empty($event['status'])) {
                                                        $status = $event['status'];
                                                    } else {
                                                        if ($now < $start) {
                                                            $status = 'Planifié';
                                                        } elseif ($now >= $start && $now <= $end) {
                                                            $status = 'En cours';
                                                        } else {
                                                            $status = 'Terminé';
                                                        }
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?= $event['id']; ?></td>
                                                    <td><?= $event['name']; ?></td>
                                                    <td><?= $event['description']; ?></td>
                                                    <td><?= $event['start_datetime']; ?></td>
                                                    <td><?= $event['end_datetime']; ?></td>
                                                    <td><?= $event['location']; ?></td>
                                                    <td><?= $event['category']; ?></td>
                                                    <td><?= $event['organizer']; ?></td>
                                                    <td><?= $status; ?></td>
                                                    <td>
                                                        <a href="updateEvent.php?id=<?= $event['id']; ?>" class="btn btn-warning">Mettre à Jour</a>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-delete" data-id="<?= $event['id']; ?>">Supprimer</button>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                </div>
            <footer>
                <p>&copy; 2025 Give4You. Tous droits réservés.</p>
                <p>
                    <a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a> | <a href="#">Conditions d'utilisation</a>
                </p>
            </footer>
            </div>
        </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('.btn-delete').click(function() {
                let eventId = $(this).data('id');
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "deleteEvent.php?id=" + eventId;
                    }
                });
            });
        });
    </script>

</body>
</html>