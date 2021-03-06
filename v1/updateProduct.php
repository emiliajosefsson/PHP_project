<?php

include '../includes/db.php';
include '../objects/Product.php';
include '../objects/User.php';

$token = "";
$error = new stdClass();

if(!isset($_GET['token'])){
    $error->message = "No session";
    $error->code = "0012";
    print_r(json_encode($error));
    die();
}
$token = $_GET['token'];

$user = new User($pdo);


if(!$user->ValidToken($token)) {
    $error->message = "Sessions is over, please log in again";
    $error->code = "0011";
    print_r(json_encode($error));
    die(); 
}  

$id = "";
$productname = "";
$price = ""; 

if(!isset($_GET['id'])){
    $error->message = "Please insert an id";
    $error->code = "0007";
    print_r(json_encode($error));
    die();
}

$id = $_GET['id'];

if(!isset($_GET['productname']) && !isset($_GET['price'])){
    $error->message = "Please insert productname or price";
    $error->code = "0020";
    print_r(json_encode($error));
    die();
} 

if(isset($_GET['productname'])){
    $productname = $_GET['productname'];
}

if(isset($_GET['price'])){
    $price = $_GET['price'];
}


$product = new Product($pdo);
print_r(json_encode($product->UpdateProduct($id, $productname, $price)));

