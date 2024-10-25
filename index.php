<?php
require_once './config/database.php';
require_once './models/EventModel.php';
require_once './controllers/EventController.php';
require_once './helpers/EventControllerHelper.php';

$request_method=$_SERVER['REQUEST_METHOD'];
$data=json_decode(file_get_contents('php://input'),true);

try {
   
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

