<?php
require_once './config.php';
require_once './models/Association.php';

$database = new Database();
$db = $database->getConnection();
$association = new Association($db);

$action = $_GET['action'] ?? 'index';

switch($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $association->create($_POST);
            header("Location: index.php");
        } else {
            include './views/create.php';
        }
        break;

    case 'edit':
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $association->update($id, $_POST);
            header("Location: index.php");
        } else {
            $assoc = $association->getById($id);
            include './views/edit.php';
        }
        break;

    case 'delete':
        $association->delete($_GET['id']);
        header("Location: index.php");
        break;

    case 'show':
        $assoc = $association->getById($_GET['id']);
        include './views/show.php';
        break;

    case 'search':
        if (!empty($_GET['country'])) {
            $data = $association->searchByCountry($_GET['country']);
            include './views/index.php';
        } else {
            header("Location: index.php");
        }
        break;
// ajouter methode statisque par pays
case 'statistics':
    $stats = $association->getStatistics();
    include './views/statistics.php';
    break;


    default:
        $data = $association->getAll();
        include './views/index.php';
        break;
}
?>
