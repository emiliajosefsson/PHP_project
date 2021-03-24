<?php

include '../includes/db.php';
include '../objects/Product.php';

if(empty($_GET['productname']) || empty($_GET['price'])) {
    echo "vänligen fyll i både produktname samt pris";
    die();

} 



$product = new Product($pdo);

echo $product->CreateProduct($_GET['productname'], $_GET['price']);
