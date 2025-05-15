<?php
include '../../controller/EventController.php';
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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css" />

    <style>
        table.table {
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e3e6f0;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        table.table th {
            background-color: #5c5c5c;
            color: white;
            text-align: center;
        }
        table.table td {
            vertical-align: middle;
            text-align: center;
        }
        table.table tbody tr:hover {
            background-color: #f8f9fc;
        }

        footer.sticky-footer {
            background-color: #f8f9fc;
            padding: 1rem 0;
            border-top: 1px solid #e3e6f0;
        }
        footer.sticky-footer span {
            color: #858796;
            font-size: 0.9rem;
        }
        footer.sticky-footer i {
            color: #5c5c5c;
        }
    </style>
</head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">Give4You</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                  
                    <li class="nav-item">
                        <a class="nav-link active" href="addEvent.php">Ajouter un événement</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
      
    
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
    
            <!-- Main Content -->
            <div id="content">
    
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>
                <!-- End of Topbar -->
    
                <!-- Begin Page Content -->
                <div class="container-fluid">
    
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Liste des Événements</h1>
                    </div>
    
                    <!-- Content Row -->
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
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
    
            <footer class="mt-5 py-4 bg-dark text-white">
        <div class="container text-center">
            <p class="mb-0"><i class="fas fa-heart text-danger"></i> Give4You &copy; 2024 - Tous droits réservés</p>
        </div>
    </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
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
