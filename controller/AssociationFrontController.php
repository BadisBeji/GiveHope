<?php
require_once './config.php';
require_once './models/Association.php';

$database = new Database();
$db = $database->getConnection();
$association = new Association($db);

$action = $_GET['action'] ?? 'index';

switch($action) {
    case 'createFront':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $association->create($_POST);
            header("Location: indexFront.php");
        } else {
            include './views/Front/createFront.php';
        }
        break;

    case 'editFront':
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $association->update($id, $_POST);
            header("Location: indexFront.php");
        } else {
            $assoc = $association->getById($id);
            include './views/edit.php';
        }
        break;

    case 'delete':
        $association->delete($_GET['id']);
        header("Location: indexFront.php");
        break;

    case 'showFront':
        $assoc = $association->getById($_GET['id']);
        include './views/Front/showFront.php';
        break;

    case 'searchFront':
        if (!empty($_GET['country'])) {
            $data = $association->searchByCountry($_GET['country']);
            include './views/Front/indexFront.php';
        } else {
            header("Location: indexFront.php");
        }
        break;

    default:
        $data = $association->getAll();
        include './views/Front/indexFront.php';
        break;
}
?>
