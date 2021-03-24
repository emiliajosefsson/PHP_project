<?php

include '../includes/db.php';
include '../objects/Product.php';


        if(empty($_GET['productname'])) {
            $error = new stdClass();
            $error->message = "Vänligen fyll i ett produktnamn";
            $error->code = "0004";
            echo json_encode($error);
            die();

        } else {
            $product = new Product($pdo);
            $product->SearchProduct($_GET['productname']);
            // echo json_encode($product->SearchProduct($_GET['productname']));

        }
