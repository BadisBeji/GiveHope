<?php
include '../../controller/EventController.php';
$eventC = new EventController();
$eventC->deleteEvent($_GET["id"]);
header('Location:eventList.php');
