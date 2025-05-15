<?php

require_once 'config.php';


require_once 'Controllers/UserController.php';

$pdo = Database::connect();
$controller = new UserController($pdo);

$action = $_GET['action'] ?? 'register';

switch ($action) {
    case 'register':
        $controller->register();
        break;
    case 'list':
        $controller->listUsers();
        break;
    case 'edit':
        $controller->editUser();
        break;
    case 'delete':
        $controller->deleteUser();
        break;
    default:
        echo "Action inconnue.";
}

?>
