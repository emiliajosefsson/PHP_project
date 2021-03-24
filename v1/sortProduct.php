<?php

include '../includes/db.php';
include '../objects/Product.php';


if($_GET['product'] != "expensive" && $_GET['product'] != "cheapest" && $_GET['product'] != "first" && $_GET['product'] != "last"){
    echo "wrong value";
    die();
} 

$product = new Product($pdo);
$product->SortProduct($_GET['product']);