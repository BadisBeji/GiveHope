<?php
require_once 'Models/User.php';

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->register(
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['cin'],
                $_POST['email'],
                $_POST['password']
            );
            header('Location: index.php?action=list');
            exit;
        }
        include 'Views/register.php';
    }

    public function listUsers() {
        $users = $this->userModel->getAllUsers();
        include 'Views/users.php';
    }

    public function editUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->updateUser(
                $_POST['id'],
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['cin'],
                $_POST['email'],
                $_POST['password']
            );
            header('Location: index.php?action=list');
            exit;
        } elseif (isset($_GET['id'])) {
            $user = $this->userModel->getUserById($_GET['id']);
            include 'Views/updateUser.php';
        }
    }

    public function deleteUser() {
        if (isset($_GET['id'])) {
            $this->userModel->deleteUser($_GET['id']);
        }
        header('Location: index.php?action=list');
        exit;
    }
}
?>
