<?php

include '../includes/db.php';
include '../objects/User.php';


$user = new User($pdo);
        
        if(empty($_GET['id'])) {
            $error = new stdClass();
            $error->message = "Vänligen fyll i ett id";
            $error->code = "0004";
            echo json_encode($error);

        } else {
            
            echo json_encode($user->DeleteUser($_GET['id']));

        }