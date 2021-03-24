<?php

include '../includes/db.php';
include '../objects/Product.php';

$product = new Product($pdo);
        
        if(empty($_GET['id'])) {
            $error = new stdClass();
            $error->message = "Vänligen fyll i ett id";
            $error->code = "0004";
            echo json_encode($error);

        } else {
            
            json_encode($product->DeleteProduct($_GET['id']));

        }

       